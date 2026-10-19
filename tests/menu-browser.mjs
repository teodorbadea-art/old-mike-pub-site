import {spawn}from'node:child_process';
import {mkdtemp,rm,writeFile,mkdir}from'node:fs/promises';
import {tmpdir}from'node:os';import{join}from'node:path';import{setTimeout as delay}from'node:timers/promises';
const chrome=process.argv[2];if(!chrome)throw Error('Chrome path required');
const profile = await mkdtemp(join(tmpdir(), 'meniu-uat-chrome-'));
const browser = spawn(chrome, [
  '--headless=new', '--no-sandbox', '--disable-gpu', '--disable-dev-shm-usage',
  '--no-first-run', '--no-default-browser-check',
  '--remote-debugging-pipe', `--user-data-dir=${profile}`, 'about:blank',
], { stdio: ['ignore', 'ignore', 'inherit', 'pipe', 'pipe'] });
const pending = new Map();
let nextId = 0, buffer = '', stopped = false;
const rejectPending = error => {
  stopped = true;
  for (const request of pending.values()) request.reject(error);
  pending.clear();
};
browser.on('error', rejectPending);
browser.on('exit', () => rejectPending(new Error('Chrome exited before completing the probe')));
browser.stdio[3].on('error', rejectPending);
browser.stdio[4].setEncoding('utf8');
browser.stdio[4].on('data', chunk => {
  buffer += chunk.toString();
  let end;
  while ((end = buffer.indexOf('\0')) >= 0) {
    const raw = buffer.slice(0, end);
    buffer = buffer.slice(end + 1);
    if (!raw) continue;
    const message = JSON.parse(raw), request = pending.get(message.id);
    if (!request) continue;
    pending.delete(message.id);
    if (message.error) request.reject(new Error(message.error.message));
    else request.resolve(message.result);
  }
});
const send = (method, params = {}, sessionId) => new Promise((resolve, reject) => {
  if (stopped) { reject(new Error('Chrome is unavailable')); return; }
  const id = ++nextId;
  pending.set(id, { resolve, reject });
  browser.stdio[3].write(JSON.stringify({ id, method, params, ...(sessionId ? { sessionId } : {}) }) + '\0');
});

let sessionId;
async function evaluate(expression,awaitPromise=false){const r=await send('Runtime.evaluate',{expression,returnByValue:true,awaitPromise},sessionId);if(r.exceptionDetails)throw Error(JSON.stringify(r.exceptionDetails));return r.result?.value;}
const checks=[];
try{
 const {targetId}=await send('Target.createTarget',{url:'about:blank'});({sessionId}=await send('Target.attachToTarget',{targetId,flatten:true}));await send('Page.enable',{},sessionId);
 await mkdir('output/menu-m3',{recursive:true});
 for(const width of[1440,390,360]){
  await send('Emulation.setDeviceMetricsOverride',{width,height:900,deviceScaleFactor:1,mobile:width<500},sessionId);
  await send('Page.navigate',{url:'http://127.0.0.1:4188/preview'},sessionId);
  const start=Date.now();while(Date.now()-start<20000){if(await evaluate("document.readyState==='complete'&&document.querySelector('.om-menu-hero img')?.complete"))break;await delay(100);}
  await evaluate('document.fonts.ready.then(()=>true)',true);
  await evaluate("Promise.all([...document.images].filter(x=>x.complete&&x.naturalWidth).map(x=>x.decode().catch(()=>{}))).then(()=>new Promise(r=>requestAnimationFrame(()=>requestAnimationFrame(r))))",true);
  const result=await evaluate(`(()=>{
   const nav=document.querySelector('.om-menu-nav-inner'),links=[...nav.querySelectorAll('a')],rows=[...document.querySelectorAll('[data-menu-item]')],hero=document.querySelector('.om-menu-hero img');
   return {width:innerWidth,scroll:document.documentElement.scrollWidth,count:rows.length,singleNavRow:new Set(links.map(x=>Math.round(x.getBoundingClientRect().top))).size===1,heroLoaded:hero.complete&&hero.naturalWidth>0,heroRect:{width:hero.getBoundingClientRect().width,height:hero.getBoundingClientRect().height},headingFont:getComputedStyle(document.querySelector('h1')).fontFamily,bodyFont:getComputedStyle(document.body).fontFamily,brokenImages:[...document.images].filter(x=>x.getBoundingClientRect().top<innerHeight&&(!x.complete||!x.naturalWidth)).map(x=>x.src),header:!!document.querySelector('.om-header'),footer:!!document.querySelector('.om-footer'),overflow:[...document.querySelectorAll('body *')].map(x=>({tag:x.tagName,cls:x.className,right:Math.round(x.getBoundingClientRect().right),width:Math.round(x.getBoundingClientRect().width)})).filter(x=>x.right>innerWidth+1).slice(0,18)};
  })()`);
  if(width===1440||width===390){await evaluate('window.scrollTo(0,0)');await delay(100);const r=await send('Page.captureScreenshot',{format:'png',captureBeyondViewport:true,clip:{x:0,y:0,width,height:width===1440?2000:2600,scale:1}},sessionId);await writeFile('output/menu-m3/'+(width===1440?'preview-desktop-top':'preview-telefon')+'.png',Buffer.from(r.data,'base64'));}
  if(result.scroll>result.width+1||result.count!==257||!result.singleNavRow||!result.heroLoaded||result.heroRect.height<300||result.brokenImages.length||!result.header||!result.footer||!result.headingFont.includes('Cormorant')||!result.bodyFont.includes('Manrope'))throw Error('Layout '+JSON.stringify(result));

  await evaluate("document.querySelector('.om-more summary').click()");
  if(!await evaluate("!!document.querySelector('.om-more[open]')"))throw Error('Native details failed');
  await evaluate("document.getElementById('pizza').scrollIntoView()");await delay(150);
  if(!await evaluate("document.querySelector('.om-menu-nav a[href=\"#pizza\"]').getAttribute('aria-current')==='location'"))throw Error('Current category tracking failed');
  if(width<500){await evaluate("document.querySelector('.om-mobile-nav summary').click()");if(!await evaluate("document.querySelector('.om-mobile-nav').open"))throw Error('Mobile header navigation failed');}
  checks.push(result);
 }
 await send('Emulation.setEmulatedMedia',{features:[{name:'prefers-reduced-motion',value:'reduce'}]},sessionId);
 if(!await evaluate("getComputedStyle(document.querySelector('.om-category-image')).animationName==='none'"))throw Error('Reduced motion ignored');
 await writeFile('output/menu-m3/browser-checks.json',JSON.stringify({pass:true,checks,reducedMotion:true,nativeDetails:true,currentCategory:true},null,2));
 console.log('PASS Chrome:1440/390/360,257rows,no horizontal scroll,fonts,hero,details,active nav,mobile header,reduced motion');
}finally{browser.kill('SIGKILL');await rm(profile,{recursive:true,force:true,maxRetries:10,retryDelay:100});}

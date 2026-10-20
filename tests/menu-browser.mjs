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
   const nav=document.querySelector('.om-menu-nav-inner'),links=[...document.querySelectorAll('.om-menu-group-switch a')],rows=[...document.querySelectorAll('[data-menu-item]')],hero=document.querySelector('.om-menu-hero img');
   return {width:innerWidth,scroll:document.documentElement.scrollWidth,count:rows.length,singleNavRow:new Set(links.map(x=>Math.round(x.getBoundingClientRect().top))).size===1,heroLoaded:hero.complete&&hero.naturalWidth>0,heroRect:{width:hero.getBoundingClientRect().width,height:hero.getBoundingClientRect().height},lunchTop:document.getElementById('pranz').getBoundingClientRect().top,mobilePortrait:innerWidth>800||Math.abs(hero.getBoundingClientRect().width/hero.getBoundingClientRect().height-.8)<.01,mobileHeroClear:innerWidth>=801||document.querySelector('.om-menu-hero-copy .eyebrow').getBoundingClientRect().top>=hero.getBoundingClientRect().bottom+16,heroStack:document.elementsFromPoint(innerWidth*.75,350).map(x=>({tag:x.tagName,cls:x.className})),heroStyles:[hero,hero.parentElement,hero.parentElement.parentElement].map(x=>{const s=getComputedStyle(x),b=x.getBoundingClientRect();return {tag:x.tagName,cls:x.className,src:x.currentSrc,rect:{x:b.x,y:b.y,w:b.width,h:b.height},opacity:s.opacity,visibility:s.visibility,z:s.zIndex,objectFit:s.objectFit,mask:s.maskImage}}),headingFont:getComputedStyle(document.querySelector('h1')).fontFamily,bodyFont:getComputedStyle(document.body).fontFamily,brokenImages:[...document.images].filter(x=>x.getBoundingClientRect().top<innerHeight&&(!x.complete||!x.naturalWidth)).map(x=>x.src),header:!!document.querySelector('.om-header'),footer:!!document.querySelector('.om-footer'),overflow:[...document.querySelectorAll('body *')].map(x=>({tag:x.tagName,cls:x.className,right:Math.round(x.getBoundingClientRect().right),width:Math.round(x.getBoundingClientRect().width)})).filter(x=>x.right>innerWidth+1).slice(0,18)};
  })()`);
  if(width===1440||width===390){await evaluate('window.scrollTo(0,0)');await delay(300);const normal=await send('Page.captureScreenshot',{format:'png'},sessionId);await writeFile('output/menu-m3/viewport-'+width+'.png',Buffer.from(normal.data,'base64'));const r=await send('Page.captureScreenshot',{format:'png',captureBeyondViewport:true,clip:{x:0,y:0,width,height:width===1440?2000:2600,scale:1}},sessionId);await writeFile('output/menu-m3/'+(width===1440?'preview-desktop-top':'preview-telefon')+'.png',Buffer.from(r.data,'base64'));}
  if(result.scroll>result.width+1||result.count!==257||!result.singleNavRow||!result.heroLoaded||result.heroRect.height<180||(width>800&&result.heroRect.height>540)||(width>800&&result.lunchTop>=900)||!result.mobilePortrait||!result.mobileHeroClear||result.brokenImages.length||!result.header||!result.footer||!result.headingFont.includes('Cormorant')||!result.bodyFont.includes('Manrope')||(width<801&&!result.heroStyles[0].src.includes('hero-meniu-mobile-')))throw Error('Layout '+JSON.stringify(result));

  await evaluate("document.querySelector('.om-more summary').click()");
  if(!await evaluate("!!document.querySelector('.om-more[open]')"))throw Error('Native details failed');
  await evaluate("document.getElementById('pizza').scrollIntoView()");await delay(150);
  if(!await evaluate("document.querySelector('.om-menu-nav a[href=\"#pizza\"]').getAttribute('aria-current')==='location'"))throw Error('Current category tracking failed');
  if(width===1440){
   const grouped=await evaluate(`(()=>{const buttons=[...document.querySelectorAll('[data-menu-group]')];return buttons.map(b=>{b.click();const visible=[...document.querySelectorAll('[data-menu-nav-group]:not([hidden]) a')];return {group:b.dataset.menuGroup,count:visible.length,reachable:visible.every(a=>{const r=a.getBoundingClientRect();return r.left>=0&&r.right<=innerWidth;})};});})()`);
   if(grouped.length!==3||grouped.some(x=>!x.reachable||!x.count))throw Error('Group categories inaccessible '+JSON.stringify(grouped));
  }
  if(width<500){await evaluate("document.querySelector('.om-mobile-nav summary').click()");if(!await evaluate("document.querySelector('.om-mobile-nav').open"))throw Error('Mobile header navigation failed');}
  await evaluate("document.getElementById('spirtoase').scrollIntoView()");await delay(200);
  const tabs=await evaluate(`(()=>{const box=document.querySelector('[data-spirit-tabs]'),tabs=[...box.querySelectorAll('[role=tab]')],panels=[...box.querySelectorAll('[role=tabpanel]')];return {count:tabs.length,labels:tabs.map(t=>t.textContent.trim()),visible:panels.filter(p=>!p.hidden).length,overflow:document.documentElement.scrollWidth>innerWidth,allRows:document.querySelectorAll('[data-menu-item]').length};})()`);
  if(tabs.count!==8||tabs.visible!==1||!tabs.labels.includes('Gin')||tabs.overflow||tabs.allRows!==257)throw Error('Spirit tabs '+JSON.stringify(tabs));
  await evaluate("document.querySelector('#tab-spirit-gin').click()");
  if(!await evaluate("!document.getElementById('spirit-gin').hidden&&document.getElementById('spirit-gin').querySelectorAll('[data-menu-item]').length===3&&document.getElementById('spirit-whisky').hidden"))throw Error('Gin tab did not switch panels');
  await evaluate("document.querySelector('#tab-spirit-gin').dispatchEvent(new KeyboardEvent('keydown',{key:'ArrowRight',bubbles:true}))");
  if(!await evaluate("document.activeElement.id==='tab-spirit-tequila'&&!document.getElementById('spirit-tequila').hidden"))throw Error('Tab keyboard navigation failed');
  if(width===390||width===1440){await evaluate("document.getElementById('spirtoase').scrollIntoView()");await delay(250);const tabShot=await send('Page.captureScreenshot',{format:'png'},sessionId);await writeFile('output/menu-m3/spirtoase-'+width+'.png',Buffer.from(tabShot.data,'base64'));}

  if(width===390){await evaluate("document.getElementById('bere').scrollIntoView()");await delay(300);const beer=await send('Page.captureScreenshot',{format:'png'},sessionId);await writeFile('output/menu-m3/bere-390.png',Buffer.from(beer.data,'base64'));}
  checks.push(result);
 }
 await send('Emulation.setEmulatedMedia',{features:[{name:'prefers-reduced-motion',value:'reduce'}]},sessionId);
 if(!await evaluate("getComputedStyle(document.querySelector('.om-category-image')).animationName==='none'"))throw Error('Reduced motion ignored');
 await writeFile('output/menu-m3/browser-checks.json',JSON.stringify({pass:true,checks,reducedMotion:true,nativeDetails:true,currentCategory:true,spiritTabs:true,tabKeyboard:true,mobileHeroClear:true},null,2));
 await send('Emulation.setScriptExecutionDisabled',{value:true},sessionId);await send('Page.navigate',{url:'http://127.0.0.1:4188/preview'},sessionId);await delay(600);if(!await evaluate("document.querySelectorAll('[data-menu-item]').length===257&&[...document.querySelectorAll('.om-spirit-panel')].every(p=>!p.hidden)&&[...document.querySelectorAll('[data-menu-nav-group]')].every(p=>!p.hidden)"))throw Error('Server HTML without JavaScript incomplete');await send('Emulation.setScriptExecutionDisabled',{value:false},sessionId);
 // Verify explicit shared stylesheet links and actual loaded fonts on all site pages.
 const siteFonts=[];await send('Network.enable',{},sessionId);
 await send('Network.setBlockedURLs',{urls:['*nonexistent-font-block-test*']},sessionId);
 for(const width of[1440,390])for(const page of['index.html','evenimente.html','galerie.html','contact.html']){
  await send('Emulation.setDeviceMetricsOverride',{width,height:900,deviceScaleFactor:1,mobile:width<500},sessionId);
  await send('Page.navigate',{url:'http://127.0.0.1:4188/'+page},sessionId);
  const start=Date.now();while(Date.now()-start<20000){if(await evaluate("document.readyState==='complete'&&!!document.querySelector('h1')"))break;await delay(100);}
  await evaluate('document.fonts.ready.then(()=>true)',true);
  const fonts=await evaluate(`(()=>{const faces=[...document.fonts];return{body:getComputedStyle(document.body).fontFamily,explicitLink:!!document.querySelector('link[href*="oldmike-design.css"]'),heading:getComputedStyle(document.querySelector('h1')).fontFamily,bodyToken:getComputedStyle(document.documentElement).getPropertyValue('--om-font-body').trim(),loadedBody:faces.some(f=>f.family.includes('Manrope')&&f.status==='loaded'),loadedHeading:faces.some(f=>f.family.includes('Cormorant')&&f.status==='loaded')}})()`);
  if(!fonts.explicitLink||!fonts.body.includes('Manrope')||!fonts.heading.includes('Cormorant')||!fonts.bodyToken||!fonts.loadedBody||!fonts.loadedHeading)throw Error('Site font regression '+JSON.stringify({page,width,fonts}));
  siteFonts.push({page,width,...fonts});
  if(page==='index.html'){const shot=await send('Page.captureScreenshot',{format:'png'},sessionId);await writeFile('output/menu-m3/index-fonturi-'+width+'.png',Buffer.from(shot.data,'base64'));}
 }
 await send('Network.setBlockedURLs',{urls:[]},sessionId);
 await writeFile('output/menu-m3/site-font-checks.json',JSON.stringify({pass:true,explicitDesignLinks:true,checks:siteFonts},null,2));
 console.log('PASS Chrome:1440/390/360,257rows,no horizontal scroll,fonts,hero,details,active nav,mobile header,reduced motion');
}finally{browser.kill('SIGKILL');await rm(profile,{recursive:true,force:true,maxRetries:10,retryDelay:100});}

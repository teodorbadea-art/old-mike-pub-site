'use strict';
function groupRequestError(values,today){
 if(!String(values.name||'').trim())return 'Completează numele.';
 if(String(values.phone||'').replace(/\D/g,'').length<7)return 'Verifică numărul de telefon.';
 if(String(values.email||'').trim()&&!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(values.email).trim()))return 'Verifică adresa de email.';
 if(!/^\d{4}-\d{2}-\d{2}$/.test(values.date||'')||values.date<today)return 'Alege data de astăzi sau o dată viitoare.';
 const calendarDate=new Date(values.date+'T00:00:00Z');
 if(!Number.isFinite(calendarDate.getTime())||calendarDate.toISOString().slice(0,10)!==values.date)return 'Verifică data evenimentului.';
 if(!Number.isInteger(Number(values.guests))||Number(values.guests)<2||Number(values.guests)>1000)return 'Verifică numărul estimat de persoane.';
 if(!values.contact_permission)return 'Bifează acordul de a fi contactat pentru această solicitare.';
 return '';
}
function openingStatus(now,hours){
 const parts=new Intl.DateTimeFormat('en-GB',{timeZone:'Europe/Bucharest',weekday:'short',hour:'2-digit',minute:'2-digit',hourCycle:'h23'}).formatToParts(now);
 const get=type=>parts.find(p=>p.type===type).value;
 const day={Mon:1,Tue:2,Wed:3,Thu:4,Fri:5,Sat:6,Sun:7}[get('weekday')];
 const minute=Number(get('hour'))*60+Number(get('minute')),range=hours[String(day)];
 const format=n=>n===1440?'24:00':String(Math.floor(n/60)).padStart(2,'0')+':'+String(n%60).padStart(2,'0');
 if(!range)return {open:false,text:'Vezi programul în Contact'};
 const [start,end]=range,open=minute>=start&&minute<end;
 return {open,text:open?'Deschis acum · până la '+format(end):minute<start?'Închis acum · deschidem azi la '+format(start):'Închis acum · vezi programul'};
}
function setupSite(){
 const configElement=document.getElementById('site-config');
 if(configElement){
  try{
   const config=JSON.parse(configElement.textContent);
   const update=()=>{const state=openingStatus(new Date(),config.hours);document.querySelectorAll('[data-opening-status]').forEach(el=>{el.textContent=state.text;el.dataset.open=String(state.open);});};
   update();setInterval(update,60000);document.addEventListener('visibilitychange',()=>{if(!document.hidden)update();});
  }catch(error){/* Keep the confirmed written schedule when calculation is unavailable. */}
 }
 document.querySelectorAll('[data-load-map]').forEach(link=>link.addEventListener('click',e=>{
  e.preventDefault();const frame=document.createElement('iframe');frame.title='Hartă Old Mike Pub, Matei Voievod67, București';frame.src=link.dataset.embed;frame.referrerPolicy='no-referrer-when-downgrade';frame.allowFullscreen=true;link.closest('.map-canvas').replaceChildren(frame);
 }));
 const toggle=document.querySelector('.nav-toggle'),nav=document.querySelector('.site-nav');
 if(toggle&&nav){
  const close=()=>{nav.classList.remove('open');toggle.setAttribute('aria-expanded','false');toggle.textContent='☰';toggle.setAttribute('aria-label','Deschide meniul');};
  toggle.addEventListener('click',()=>{const opened=nav.classList.toggle('open');toggle.setAttribute('aria-expanded',String(opened));toggle.textContent=opened?'×':'☰';toggle.setAttribute('aria-label',opened?'Închide meniul':'Deschide meniul');});
  document.addEventListener('keydown',e=>{if(e.key==='Escape'&&nav.classList.contains('open')){close();toggle.focus();}});
  document.addEventListener('click',e=>{if(!nav.contains(e.target)&&!toggle.contains(e.target))close();});
  nav.querySelectorAll('a').forEach(a=>a.addEventListener('click',close));
 }
 const dialog=document.querySelector('#image-viewer');
 if(dialog){
  let opener;
  document.querySelectorAll('[data-gallery]').forEach(b=>b.addEventListener('click',()=>{opener=b;const original=b.querySelector('img'),large=dialog.querySelector('img');large.removeAttribute('srcset');large.src=original.dataset.full||original.src;large.alt=original.alt;dialog.showModal();}));
  dialog.querySelector('button').addEventListener('click',()=>dialog.close());
  dialog.addEventListener('click',e=>{if(e.target===dialog)dialog.close();});
  dialog.addEventListener('close',()=>opener?.focus());
 }
 const filters=document.querySelectorAll('.filter');
 filters.forEach(b=>b.addEventListener('click',()=>{filters.forEach(f=>f.setAttribute('aria-pressed',String(f===b)));document.querySelectorAll('[data-event-category]').forEach(card=>card.hidden=b.dataset.filter!=='all'&&card.dataset.eventCategory!==b.dataset.filter);}));
 const request=document.querySelector('#group-request-form');
 if(request){
  const parts=new Intl.DateTimeFormat('en-CA',{timeZone:'Europe/Bucharest',year:'numeric',month:'2-digit',day:'2-digit'}).formatToParts(new Date());
  const value=type=>parts.find(p=>p.type===type).value,today=`${value('year')}-${value('month')}-${value('day')}`;
  request.elements.date.min=today;
  request.addEventListener('submit',e=>{
   e.preventDefault();const values=Object.fromEntries(new FormData(request)),status=document.querySelector('#request-status'),summary=document.querySelector('#request-summary'),details=document.querySelector('#request-summary-details');
   summary.hidden=true;status.textContent='';const error=groupRequestError(values,today);
   if(error){status.textContent=error;return;}
   details.replaceChildren();
   const rows=[['Nume',values.name.trim()],['Telefon',values.phone],['Data',values.date.split('-').reverse().join('.')+(values.time?' · '+values.time:'')],['Persoane',values.guests],['Ocazie',values.occasion],['Zona preferată',values.area]];
   if(values.email?.trim())rows.push(['Email',values.email.trim()]);
   if(values.details?.trim())rows.push(['Detalii',values.details.trim()]);
   rows.forEach(([label,content])=>{const dt=document.createElement('dt'),dd=document.createElement('dd');dt.textContent=label;dd.textContent=content;details.append(dt,dd);});
   summary.hidden=false;status.textContent='Cererea este pregătită pentru verificare. În această versiune nu se trimite și nu se salvează.';
  });
 }
}
if(typeof document!=='undefined')setupSite();
if(typeof module!=='undefined')module.exports={openingStatus,groupRequestError};

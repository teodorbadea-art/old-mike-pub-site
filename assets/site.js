'use strict';
function groupRequestError(values,today){
 if(!String(values.name||'').trim())return 'Completează numele.';
 if(String(values.phone||'').replace(/\D/g,'').length<7)return 'Verifică numărul de telefon.';
 if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(values.email||'')))return 'Verifică adresa de email.';
 if(!/^\d{4}-\d{2}-\d{2}$/.test(values.date||'')||values.date<today)return 'Alege data de astăzi sau o dată viitoare.';
 const calendarDate=new Date(values.date+'T00:00:00Z');
 if(!Number.isFinite(calendarDate.getTime())||calendarDate.toISOString().slice(0,10)!==values.date)return 'Verifică data evenimentului.';
 if(!Number.isInteger(Number(values.guests))||Number(values.guests)<2||Number(values.guests)>1000)return 'Verifică numărul estimat de persoane.';
 if(!values.contact_permission)return 'Bifează acordul de a fi contactat pentru această solicitare.';
 return '';
}
function setupSite(){
 const toggle=document.querySelector('.nav-toggle'),nav=document.querySelector('.site-nav');
 if(toggle&&nav){
  const close=()=>{nav.classList.remove('open');toggle.setAttribute('aria-expanded','false');toggle.setAttribute('aria-label','Deschide meniul');};
  toggle.addEventListener('click',()=>{const opened=nav.classList.toggle('open');toggle.setAttribute('aria-expanded',String(opened));toggle.setAttribute('aria-label',opened?'Închide meniul':'Deschide meniul');});
  document.addEventListener('keydown',e=>{if(e.key==='Escape'&&nav.classList.contains('open')){close();toggle.focus();}});
  document.addEventListener('click',e=>{if(!nav.contains(e.target)&&!toggle.contains(e.target))close();});
  nav.querySelectorAll('a').forEach(a=>a.addEventListener('click',close));
 }
 const dialog=document.querySelector('#image-viewer');
 if(dialog){
  let opener;
  document.querySelectorAll('[data-gallery]').forEach(b=>b.addEventListener('click',()=>{opener=b;const original=b.querySelector('img'),large=dialog.querySelector('img');large.src=original.src;large.alt=original.alt;dialog.showModal();}));
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
   const rows=[['Nume',values.name.trim()],['Telefon',values.phone],['Email',values.email],['Data',values.date.split('-').reverse().join('.')+(values.time?' · '+values.time:'')],['Persoane',values.guests],['Ocazie',values.occasion],['Zona preferată',values.area]];
   if(values.details?.trim())rows.push(['Detalii',values.details.trim()]);
   rows.forEach(([label,content])=>{const dt=document.createElement('dt'),dd=document.createElement('dd');dt.textContent=label;dd.textContent=content;details.append(dt,dd);});
   summary.hidden=false;status.textContent='Cererea este pregătită pentru verificare. În această versiune nu se trimite și nu se salvează.';
  });
 }
}
setupSite();

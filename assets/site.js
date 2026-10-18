'use strict';
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
}
setupSite();

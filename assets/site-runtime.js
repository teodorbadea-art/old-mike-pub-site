'use strict';
// Included only by the cPanel packaging step. Local design reviews stay inert.
(async function(){
 const endpoint='api/index.php';
 const form=document.getElementById('group-request-form');
 if(form&&form.dataset.mode==='server'){
  const button=form.querySelector('button[type="submit"]'),status=document.getElementById('request-status');
  let credentials=null,busy=false,done=false;button.disabled=true;
  try{const response=await fetch(endpoint+'?action=form',{credentials:'same-origin',cache:'no-store'});const result=await response.json();if(!response.ok)throw Error(result.message);credentials=result;button.disabled=false;}
  catch(error){status.textContent='Cererile online nu sunt încă disponibile. Sună la 0787 366 166.';}
  form.addEventListener('submit',async event=>{
   event.preventDefault();if(busy||done||!credentials)return;
   if(!form.reportValidity())return;
   const body=new FormData(form);body.set('csrf',credentials.csrf);body.set('submission',credentials.submission);
   busy=true;button.disabled=true;status.textContent='Înregistrăm cererea…';
   try{
    const response=await fetch(endpoint+'?action=request',{method:'POST',body,credentials:'same-origin',cache:'no-store'});const result=await response.json();
    if(!response.ok||!result.ok)throw Error(result.message||'Cererea nu a putut fi înregistrată.');
    done=true;status.textContent=result.message+' Referință: '+result.reference.slice(0,8);button.textContent='Cerere înregistrată';
   }catch(error){status.textContent=(error.message||'Conexiunea a fost întreruptă.')+' Poți încerca din nou; aceeași cerere nu se dublează.';}
   finally{busy=false;button.disabled=done;}
  });
 }
 const empty=document.querySelector('.empty-events');
 if(!empty||!document.querySelector('.events-bottom'))return;
 try{
  const response=await fetch(endpoint+'?action=events',{cache:'no-store'});if(!response.ok)return;const result=await response.json();
  if(!Array.isArray(result.events)||!result.events.length)return;
  const grid=document.createElement('div');grid.className='event-grid';
  const formatter=new Intl.DateTimeFormat('ro-RO',{timeZone:'Europe/Bucharest',dateStyle:'medium',timeStyle:'short'});
  for(const item of result.events){
   const imageUrl=new URL(item.poster,location.href);if(imageUrl.origin!==location.origin||!['sport','other'].includes(item.category))continue;
   const card=document.createElement('article');card.className='event-poster';card.dataset.eventCategory=item.category;
   const image=document.createElement('img');image.src=imageUrl.href;image.alt='Poster: '+item.title;image.loading='lazy';image.decoding='async';
   const title=document.createElement('h2');title.textContent=item.title;
   const date=document.createElement('p');date.textContent=formatter.format(new Date(item.starts))+' · București';
   const call=document.createElement('a');call.className='button ghost';call.href='tel:+40787366166';call.textContent='Rezervă telefonic';
   card.append(image,title,date,call);grid.append(card);
  }
  if(!grid.childElementCount)return;
  document.querySelectorAll('.filters').forEach(el=>el.remove());
  if(new Set(result.events.map(e=>e.category)).size>1){
   const filters=document.createElement('div');filters.className='filters';filters.setAttribute('role','group');filters.setAttribute('aria-label','Filtrează evenimentele');
   for(const [key,label] of [['all','Toate'],['sport','Sport'],['other','Alte evenimente']]){
    const button=document.createElement('button');button.type='button';button.className='filter';button.textContent=label;button.dataset.filter=key;button.setAttribute('aria-pressed',String(key==='all'));
    button.addEventListener('click',()=>{filters.querySelectorAll('button').forEach(b=>b.setAttribute('aria-pressed',String(b===button)));grid.querySelectorAll('article').forEach(card=>card.hidden=key!=='all'&&card.dataset.eventCategory!==key);});filters.append(button);
   }empty.before(filters);
  }
  empty.replaceWith(grid);
 }catch(error){/* Retain useful static phone/contact information if the server is unavailable. */}
})();

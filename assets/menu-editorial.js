/* Menu remains fully usable without JS. This only marks the current section. */
(()=>{
 'use strict';
 const nav=document.querySelector('.om-menu-nav-inner');if(!nav||!('IntersectionObserver'in window))return;
 const links=[...nav.querySelectorAll('a[href^="#"]')];
 const entries=links.map(link=>({link,section:document.getElementById(link.hash.slice(1))})).filter(x=>x.section);
 let scheduled=false;
 const mark=()=>{
  scheduled=false;let active=entries[0];
  for(const item of entries)if(item.section.getBoundingClientRect().top<=120)active=item;
  if(!active)return;
  let changed=false;
  for(const{link}of entries){if(link===active.link){changed=link.getAttribute('aria-current')!=='location';link.setAttribute('aria-current','location');}else link.removeAttribute('aria-current');}
  if(changed){const rect=active.link.getBoundingClientRect(),bounds=nav.getBoundingClientRect();if(rect.left<bounds.left||rect.right>bounds.right)nav.scrollTo({left:nav.scrollLeft+rect.left-bounds.left-24,behavior:'auto'});}
 };
 const schedule=()=>{if(!scheduled){scheduled=true;requestAnimationFrame(mark);}};
 const observer=new IntersectionObserver(schedule,{rootMargin:'-80px 0px -55% 0px',threshold:[0,.1,.5,1]});entries.forEach(x=>observer.observe(x.section));
 window.addEventListener('scroll',schedule,{passive:true});window.addEventListener('resize',schedule,{passive:true});document.addEventListener('toggle',schedule,true);mark();
})();

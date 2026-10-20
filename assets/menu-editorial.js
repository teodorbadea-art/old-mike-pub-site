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

/* Progressively enhance server-rendered spirit sections into accessible tabs. */
(()=>{
 'use strict';
 document.querySelectorAll('[data-spirit-tabs]').forEach(container=>{
  const nav=container.querySelector('.om-spirit-tabs');
  const tabs=[...nav.querySelectorAll('a')],panels=tabs.map(tab=>document.getElementById(tab.hash.slice(1)));
  if(tabs.length<2||panels.some(panel=>!panel))return;
  nav.setAttribute('role','tablist');nav.setAttribute('aria-label','Tipuri de spirtoase');
  const activate=(index,focus=false)=>{
   tabs.forEach((tab,i)=>{tab.setAttribute('aria-selected',String(i===index));tab.tabIndex=i===index?0:-1;panels[i].hidden=i!==index;});
   if(focus){tabs[index].focus({preventScroll:true});const r=tabs[index].getBoundingClientRect(),b=nav.getBoundingClientRect();if(r.left<b.left||r.right>b.right)nav.scrollTo({left:nav.scrollLeft+r.left-b.left-12,behavior:'auto'});}
  };
  tabs.forEach((tab,index)=>{
   tab.setAttribute('role','tab');tab.setAttribute('aria-controls',panels[index].id);
   panels[index].setAttribute('role','tabpanel');panels[index].setAttribute('aria-labelledby',tab.id);panels[index].tabIndex=0;
   tab.addEventListener('click',event=>{event.preventDefault();activate(index,true);});
   tab.addEventListener('keydown',event=>{let next=index;if(event.key==='ArrowRight')next=(index+1)%tabs.length;else if(event.key==='ArrowLeft')next=(index-1+tabs.length)%tabs.length;else if(event.key==='Home')next=0;else if(event.key==='End')next=tabs.length-1;else return;event.preventDefault();activate(next,true);});
  });
  const requested=tabs.findIndex(tab=>tab.hash===location.hash);activate(requested>=0?requested:0);
  container.classList.add('is-enhanced');
 });
})();

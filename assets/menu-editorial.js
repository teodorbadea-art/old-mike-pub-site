/* All category links remain visible without JavaScript. */
(()=>{
 'use strict';
 const nav=document.querySelector('.om-menu-nav');if(!nav)return;
 const scroller=nav.querySelector('.om-menu-nav-inner'),buttons=[...nav.querySelectorAll('[data-menu-group]')],groups=[...nav.querySelectorAll('[data-menu-nav-group]')];
 const height=()=>document.documentElement.style.setProperty('--om-menu-nav-height',nav.offsetHeight+'px');
 const activate=id=>{groups.forEach(g=>g.hidden=g.dataset.menuNavGroup!==id);buttons.forEach(b=>b.setAttribute('aria-pressed',String(b.dataset.menuGroup===id)));height();};
 nav.classList.add('is-grouped');buttons.forEach(b=>{b.setAttribute('role','button');b.addEventListener('click',()=>activate(b.dataset.menuGroup));b.addEventListener('keydown',e=>{if(e.key===' '){e.preventDefault();b.click();}});});
 const entries=[...nav.querySelectorAll('a[href^="#"]')].map(link=>({link,section:document.getElementById(link.hash.slice(1)),group:link.dataset.menuGroup||link.parentElement.dataset.menuNavGroup})).filter(x=>x.section).sort((a,b)=>a.section.compareDocumentPosition(b.section)&Node.DOCUMENT_POSITION_FOLLOWING?-1:1);
 let scheduled=false;
 const mark=()=>{scheduled=false;let active=entries[0];for(const entry of entries)if(entry.section.getBoundingClientRect().top<=nav.offsetHeight+24)active=entry;if(!active)return;activate(active.group);entries.forEach(({link})=>{if(link===active.link)link.setAttribute('aria-current','location');else link.removeAttribute('aria-current');});const rect=active.link.getBoundingClientRect(),bounds=scroller.getBoundingClientRect();if(active.link.parentElement.matches('[data-menu-nav-group]')&&(rect.left<bounds.left||rect.right>bounds.right))scroller.scrollTo({left:scroller.scrollLeft+rect.left-bounds.left-12,behavior:'auto'});};
 const schedule=()=>{if(!scheduled){scheduled=true;requestAnimationFrame(mark);}};
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

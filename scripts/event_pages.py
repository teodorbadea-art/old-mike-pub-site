"""Render only valid, not-ended published records; no invented event examples."""
from datetime import datetime,timezone
from html import escape
from pathlib import Path
from zoneinfo import ZoneInfo
ROOT=Path(__file__).resolve().parents[1]

def active_events(records,now=None):
    now=now or datetime.now(timezone.utc);out=[]
    if not isinstance(records,list):raise ValueError('events.json must be a list')
    for r in records:
        if r.get('published') is not True:continue
        starts=datetime.fromisoformat(r['starts_at'].replace('Z','+00:00'));ends=datetime.fromisoformat(r['ends_at'].replace('Z','+00:00'))
        if starts.tzinfo is None or ends.tzinfo is None or ends<=starts:raise ValueError('Event dates need timezone and positive duration')
        if ends<=now:continue
        if r.get('category') not in ['sport','other']:raise ValueError('Invalid event category')
        path=Path(r['poster']);full=(ROOT/path).resolve()
        if not path.as_posix().startswith('assets/events/') or not full.is_relative_to(ROOT/'assets/events') or full.suffix.lower() not in ['.jpg','.jpeg','.png','.webp'] or not full.is_file():raise ValueError('Invalid event poster')
        if not str(r['title']).strip():raise ValueError('Event title is required')
        out.append((starts,r))
    return [r for start,r in sorted(out,key=lambda x:x[0])]

def render_events(records,intro,button,now=None):
    records=active_events(records,now)
    if not records:
        return '<main id="continut" class="page-main">'+intro('Ne strângem la Old Mike','Seri de trecut<br><em>în calendar.</em>','Meciuri, întâlniri și evenimente. Aici găsești ce urmează la Old Mike.')+'<section class="empty-events"><h2>Pregătim următoarele<br><em>seri Old Mike.</em></h2><p>Nu sunt evenimente publicate în acest moment.</p>'+button('tel:+40787366166','Întreabă-ne despre rezervări',True)+'</section></main>'
    cards=[]
    for r in records:
        dt=datetime.fromisoformat(r['starts_at'].replace('Z','+00:00')).astimezone(ZoneInfo('Europe/Bucharest'))
        cards.append(f'<article class="event-poster" data-event-category="{r["category"]}"><img src="{escape(r["poster"],quote=True)}" alt="Poster: {escape(r["title"],quote=True)}" loading="lazy"><h2>{escape(r["title"])}</h2><p>{dt.strftime("%d.%m.%Y · %H:%M")} · București</p>{button("tel:+40787366166","Rezervă telefonic",True)}</article>')
    filters='<div class="filters" role="group" aria-label="Filtrează evenimentele"><button class="filter" data-filter="all" aria-pressed="true" type="button">Toate</button><button class="filter" data-filter="sport" aria-pressed="false" type="button">Sport</button><button class="filter" data-filter="other" aria-pressed="false" type="button">Alte evenimente</button></div>' if len(set(r['category'] for r in records))>1 else ''
    return '<main id="continut" class="page-main">'+intro('Ne strângem la Old Mike','Seri de trecut<br><em>în calendar.</em>','Alege următoarea ieșire cu gașca.')+filters+'<div class="event-grid">'+''.join(cards)+'</div></main>'

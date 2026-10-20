<?php
declare(strict_types=1);
namespace OldMikeMenu;
function label(string $category,array $config):string{return $config['labels'][$category]??name($category);}
function model(array $rows,array $config,string $root):array{
    $groups=[];foreach($rows as $r)$groups[$r['group_code']][$r['category_name']][]=$r;
    $ordered=[];foreach(['Mancare','Bauturi','Narghilea'] as $key)if(isset($groups[$key]))$ordered[$key]=$groups[$key];
    $groups=$ordered+array_diff_key($groups,$ordered);
    $sections=[];$used=[];
    foreach($config['sections'] as $s){
        $s['lists']=[];
        foreach($s['categories'] as $category)if(isset($groups[$s['group']][$category]))$s['lists'][$category]=$groups[$s['group']][$category];
        $s['has_image']=$s['image']&&is_file($root.'/assets/images/'.$s['image'].'.webp');
        if(!$s['lists']||(!$s['has_image']&&!($s['standalone']??false)))continue;
        foreach($s['lists'] as $category=>$list)$used[$s['group']][$category]=true;
        $sections[$s['group']][]=$s;
    }
    $compact=[];foreach($groups as $group=>$categories)foreach($categories as $category=>$list)if(!isset($used[$group][$category]))$compact[$group][$category]=$list;
    return ['groups'=>$groups,'sections'=>$sections,'compact'=>$compact];
}
function picture(string $stem,string $alt,string $root,bool $hero=false):string{
    $base='assets/images/'.$stem;$path=$base.'.webp';$size=@getimagesize($root.'/'.$path);if(!$size)return '';
    $sets=[];foreach(['avif','webp'] as $format){$items=[];foreach([480,800]as $width)$items[]=$base.'-'.$width.'.'.$format.' '.$width.'w';$items[]=$base.'.'.$format.' '.$size[0].'w';$sets[$format]=implode(', ',$items);}
    $sizes=$hero?'100vw':'(max-width:800px) 100vw, (max-width:1500px) 55vw, 800px';$mobile='';
    // Check the file shipped in the package, not a nonexistent mobile master.
    if($hero&&is_file($root.'/'.$base.'-mobile-800.webp')){
        foreach(['avif','webp']as $format)$mobile.='<source media="(max-width:800px)" type="image/'.$format.'" srcset="'.e($base.'-mobile-480.'.$format).' 480w, '.e($base.'-mobile-800.'.$format).' 800w, '.e($base.'-mobile.'.$format).' '.getimagesize($root.'/'.$base.'-mobile.webp')[0].'w" sizes="100vw">';
    }
    return '<picture>'.$mobile.'<source type="image/avif" srcset="'.e($sets['avif']).'" sizes="'.e($sizes).'"><img src="'.e($path).'" srcset="'.e($sets['webp']).'" sizes="'.e($sizes).'" width="'.$size[0].'" height="'.$size[1].'" alt="'.e($alt).'" '.($hero?'fetchpriority="high" loading="eager"':'loading="lazy"').' decoding="async"></picture>';
}
function rows_html(array $rows):string{
    $html='';foreach($rows as $r){
        $displayName=$r['name_ro'];$isDrink=$r['group_code']==='Bauturi';$portion=portion($r['portion_display']??'');$desc=description($r['description_ro']??'');$inline='';
        if($isDrink){$drink=drink_details($r);$inline=$drink['inline'];$desc=$drink['description'];$displayName=$drink['name'];}
        $name='<span class="om-dish-name">'.e(name($displayName)).'</span>';
        if($isDrink)$name='<span class="om-drink-heading">'.$name.($inline!==''?'<span class="om-drink-measure">'.e($inline).'</span>':'').'</span>';
        $meta=(!empty($r['featured'])?'<span class="om-recommended">Recomandat</span>':'').(!$isDrink&&$portion!==''?'<span class="om-dish-portion">'.e($portion).'</span>':'');
        $html.='<li class="om-dish'.($isDrink?' om-dish-drink':'').'" data-menu-item><div class="om-dish-line">'.$name.'<span class="om-dish-dots" aria-hidden="true"></span><strong class="om-dish-price">'.e(price($r['price'])).'</strong></div>'.($meta!==''?'<div class="om-dish-meta">'.$meta.'</div>':'').($desc!==''?'<p class="om-dish-description">'.e($desc).'</p>':'').'</li>';
    }return $html;
}
/** Presentation grouping only; published category and product data stay intact. */
function spirit_panels(array $lists,array $config):array{
    $panels=[];$used=[];
    foreach($config['spirit_tabs']as $tab){$rows=[];
        foreach($tab['categories']as $category)foreach($lists[$category]??[]as $index=>$row){
            if(isset($used[$category][$index]))continue;
            if(isset($tab['match_name'])&&!preg_match($tab['match_name'],$row['name_ro']))continue;
            if(isset($tab['exclude_name'])&&preg_match($tab['exclude_name'],$row['name_ro']))continue;
            $used[$category][$index]=true;$rows[]=$row;
        }
        if($rows)$panels[]=['id'=>'spirit-'.$tab['id'],'label'=>$tab['label'],'rows'=>$rows];
    }
    foreach($lists as $category=>$rows){$remaining=[];foreach($rows as $index=>$row)if(!isset($used[$category][$index]))$remaining[]=$row;
        if($remaining)$panels[]=['id'=>'spirit-'.substr(hash('sha256',$category),0,10),'label'=>label($category,$config),'rows'=>$remaining];
    }return $panels;
}
function spirits_html(array $lists,array $config):string{
    $panels=spirit_panels($lists,$config);$html='<div class="om-spirits" data-spirit-tabs><nav class="om-spirit-tabs" aria-label="Tipuri de spirtoase">';
    foreach($panels as $p)$html.='<a id="tab-'.e($p['id']).'" href="#'.e($p['id']).'">'.e($p['label']).'</a>';
    $html.='</nav>';
    foreach($panels as $p)$html.='<section class="om-spirit-panel" id="'.e($p['id']).'" aria-labelledby="title-'.e($p['id']).'"><h3 id="title-'.e($p['id']).'" class="om-subcategory-title">'.e($p['label']).'</h3>'.dishes($p['rows'],$p['label']).'</section>';
    return $html.'</div>';
}
function dishes(array $rows,string $category):string{
    $first=count($rows)<=8?count($rows):6;
    $html='<ul class="om-dishes">'.rows_html(array_slice($rows,0,$first)).'</ul>';
    if($first<count($rows))$html.='<details class="om-more"><summary>Încă '.(count($rows)-$first).' '.(in_array($rows[0]['group_code']??'',['Bauturi','Narghilea'],true)?'opțiuni':'preparate').'<span class="sr-only"> · '.e($category).'</span><span aria-hidden="true">＋</span></summary><ul class="om-dishes">'.rows_html(array_slice($rows,$first)).'</ul></details>';
    return $html;
}
function item_schema(array $row):array{
    $displayName=name($row['name_ro']);$desc=description($row['description_ro']??'');
    if($row['group_code']==='Bauturi'){$details=drink_details($row);$displayName=name($details['name']);$desc=implode(' · ',array_filter([$details['inline'],$details['description']],static fn($v)=>$v!==''));}
    return ['@type'=>'MenuItem','name'=>$displayName,'description'=>$desc,'offers'=>['@type'=>'Offer','price'=>number_format((float)$row['price'],2,'.',''),'priceCurrency'=>'RON']];
}
function json_ld(array $model):array{
    $sections=[];$config=require __DIR__.'/config.php';
    foreach($model['groups'] as $group=>$categories)foreach($categories as $category=>$rows){
        $sections[]=['@type'=>'MenuSection','name'=>label($category,$config),'hasMenuItem'=>array_map(__NAMESPACE__.'\\item_schema',$rows)];
    }
    return ['@context'=>'https://schema.org','@type'=>'Restaurant','@id'=>'https://garden.oldmike.ro/#restaurant','name'=>'Old Mike Pub','url'=>'https://garden.oldmike.ro/','telephone'=>'+40787366166','address'=>['@type'=>'PostalAddress','streetAddress'=>'Strada Matei Voievod 67','addressLocality'=>'București','addressCountry'=>'RO'],'hasMenu'=>['@type'=>'Menu','@id'=>'https://garden.oldmike.ro/meniu.html#meniu','name'=>'Meniu Old Mike Pub','url'=>'https://garden.oldmike.ro/meniu.html','hasMenuSection'=>$sections]];
}
function render(array $menu,string $root,bool $preview=false):string{
    $config=require __DIR__.'/config.php';$m=model($menu['rows'],$config,$root);$lunch=$config['lunch'];
    $groupNames=['Mancare'=>'Mâncare','Bauturi'=>'Băuturi','Narghilea'=>'Narghilea'];
    $groupIds=['Mancare'=>'mancare','Bauturi'=>'bauturi','Narghilea'=>'narghilea'];
    $total=count($menu['rows']);$description='Meniul Old Mike Pub: preparate și băuturi, cu prețuri actualizate. Strada Matei Voievod 67, București. Rezervări: 0787 366 166.';
    ob_start();
    ?>
<!doctype html><html lang="ro"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover"><meta name="robots" content="<?= $preview?'noindex,nofollow':'index,follow' ?>"><meta name="theme-color" content="#100e0b"><title>Meniu Old Mike Pub București | Preparate și prețuri actualizate</title><meta name="description" content="<?= e($description) ?>"><link rel="canonical" href="https://garden.oldmike.ro/meniu.html"><meta property="og:type" content="website"><meta property="og:locale" content="ro_RO"><meta property="og:site_name" content="Old Mike Pub"><meta property="og:title" content="Meniu Old Mike Pub · Gusturi pentru seri bune"><meta property="og:description" content="<?= e($description) ?>"><meta property="og:url" content="https://garden.oldmike.ro/meniu.html"><meta property="og:image" content="https://garden.oldmike.ro/assets/images/meniu/hero-meniu.webp"><meta property="og:image:width" content="1536"><meta property="og:image:height" content="864"><meta name="twitter:card" content="summary_large_image"><link rel="icon" href="assets/brand/favicon-32.png"><link rel="stylesheet" href="assets/oldmike-design.css?v=m33"><link rel="stylesheet" href="assets/site.css?v=m33"><script type="application/ld+json"><?= json_encode(json_ld($m),JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_INVALID_UTF8_SUBSTITUTE) ?></script></head>
<body class="menu-editorial">
<?php if($preview): ?><aside class="om-preview-notice">Previzualizare · <?= $total ?> produse și prețuri reale<?= $menu['at']?' · '.e((new \DateTimeImmutable('@'.$menu['at']))->setTimezone(new \DateTimeZone('Europe/Bucharest'))->format('d.m.Y H:i')):'' ?> · Site-ul oficial nu a fost modificat.</aside><?php endif ?>
<?= file_get_contents(__DIR__.'/header.html') ?>
<main id="continut">
<section class="om-menu-hero" aria-labelledby="menu-title"><figure class="om-menu-hero-image"><?= picture($config['hero']['image'],$config['hero']['alt'],$root,true) ?></figure><div class="om-menu-hero-copy"><p class="eyebrow">La ce ți-e poftă?</p><h1 id="menu-title">Gusturi pentru<br><em>seri bune.</em></h1><p>Burgeri, pizza, un prânz bun.<br>Și încă un motiv să mai stai.</p><div class="om-hero-actions"><a class="button" href="#pranz">Vezi meniul de prânz <span aria-hidden="true">↓</span></a><a class="button ghost" href="tel:+40787366166">Sună · Rezervă</a></div></div></section>
<div class="om-menu-wrap">
<section class="om-lunch" id="pranz" aria-labelledby="lunch-title"><div><p class="om-kicker">Pauza cu gust bun</p><h2 id="lunch-title">Marea <em>Prânzeală.</em></h2><p class="om-lunch-parts"><?= e($lunch['parts']) ?></p><p class="om-lunch-hours"><?= e($lunch['hours']) ?></p></div><div class="om-lunch-price"><strong><?= e(str_replace(' lei','',price($lunch['price']))) ?><small>lei</small></strong><a class="om-text-link" href="https://meniu.oldmike.ro/m/old-mike/loc-oldmike-matei">Deschide meniul electronic <span aria-hidden="true">↗</span></a></div></section>
</div>
<nav class="om-menu-nav" aria-label="Categoriile meniului"><div class="om-menu-nav-layout"><div class="om-menu-group-switch" aria-label="Grupele meniului">
<?php foreach($m['groups'] as $g=>$categories):$gid=$groupIds[$g]??('grup-'.substr(hash('sha256',$g),0,8)); ?><a href="#<?= e($gid) ?>" data-menu-group="<?= e($gid) ?>"><?= e($groupNames[$g]??name($g)) ?></a><?php endforeach ?>
</div><div class="om-menu-nav-inner">
<?php foreach($m['groups'] as $g=>$categories):$gid=$groupIds[$g]??('grup-'.substr(hash('sha256',$g),0,8)); ?><div class="om-menu-category-links" data-menu-nav-group="<?= e($gid) ?>"><?php foreach($m['sections'][$g]??[] as $s): ?><a href="#<?= e($s['id']) ?>"><?= e(rtrim($s['title'],'.')) ?></a><?php endforeach ?><?php if(!empty($m['compact'][$g])): ?><a href="#altele-<?= e($gid) ?>">Și altele<span class="sr-only"> · <?= e($groupNames[$g]??$g) ?></span></a><?php endif ?></div><?php endforeach ?>
</div></div></nav>
<div class="om-menu-wrap om-menu-body">
<?php if(!$menu['rows']): ?><section class="om-menu-fallback"><h2>Pofta rămâne.<br><em>Ne auzim la telefon.</em></h2><p>Pentru meniul complet și detalii, deschide meniul electronic sau sună-ne.</p><a class="button" href="https://meniu.oldmike.ro/m/old-mike/loc-oldmike-matei">Deschide meniul electronic ↗</a></section><?php endif ?>
<?php $number=0;foreach($m['groups'] as $g=>$categories):$gid=$groupIds[$g]??('grup-'.substr(hash('sha256',$g),0,8)); ?>
<section class="om-menu-group" id="<?= e($gid) ?>" aria-labelledby="title-<?= e($gid) ?>"><div class="om-group-heading"><span class="om-kicker">Pe îndelete</span><h2 id="title-<?= e($gid) ?>"><?= e($groupNames[$g]??name($g)) ?><span aria-hidden="true">.</span></h2></div>
<?php foreach($m['sections'][$g]??[] as $s):$number++; ?>
<section class="om-category <?= !$s['has_image']?'om-category-text':($number%2===0?'om-category-reverse':'') ?>" id="<?= e($s['id']) ?>" aria-labelledby="title-<?= e($s['id']) ?>"><?php if($s['has_image']): ?><figure class="om-category-figure"><div class="om-category-image"><?= picture($s['image'],$s['alt'],$root) ?></div></figure><?php endif ?><div class="om-category-copy"><p class="om-kicker"><?= str_pad((string)$number,2,'0',STR_PAD_LEFT) ?> / <?= e($s['kicker']) ?></p><h2 id="title-<?= e($s['id']) ?>"><?= e($s['title']) ?><br><em><?= e($s['italic']) ?></em></h2>
<?php if($s['id']==='spirtoase'): ?><?= spirits_html($s['lists'],$config) ?><?php else: ?><?php foreach($s['lists'] as $c=>$rows):if(count($s['lists'])>1&&lower(rtrim(label($c,$config),'. '))!==lower(rtrim($s['title'],'. '))): ?><h3 class="om-subcategory-title"><?= e(label($c,$config)) ?></h3><?php endif ?><?= dishes($rows,label($c,$config)) ?><?php endforeach ?><?php endif ?></div></section>
<?php endforeach ?>
<?php if(!empty($m['compact'][$g])): ?><section class="om-compact" id="altele-<?= e($gid) ?>" aria-labelledby="title-altele-<?= e($gid) ?>"><div class="om-compact-heading"><p class="om-kicker">Și încă ceva bun</p><h2 id="title-altele-<?= e($gid) ?>">Și <em>altele.</em></h2></div><div class="om-compact-grid">
<?php foreach($m['compact'][$g] as $c=>$rows): ?><section class="om-compact-category" aria-labelledby="compact-<?= substr(hash('sha256',$g.'/'.$c),0,12) ?>"><h3 id="compact-<?= substr(hash('sha256',$g.'/'.$c),0,12) ?>"><?= e(label($c,$config)) ?></h3><?= dishes($rows,label($c,$config)) ?></section><?php endforeach ?>
</div></section><?php endif ?></section><?php endforeach ?>
<section class="om-menu-end" aria-label="Mai multe informații despre meniu"><p class="om-kicker">Ne vedem la masă</p><h2>Ai ales?<br><em>Noi am pus masa.</em></h2><a class="button" href="https://meniu.oldmike.ro/m/old-mike/loc-oldmike-matei">Deschide meniul electronic <span aria-hidden="true">↗</span></a><p>Poze de produs și detalii în meniul electronic.</p><p class="om-menu-fineprint">Prețurile se actualizează automat din meniul restaurantului.<br>Informații despre alergeni: întreabă ospătarul.<br>Imagine ilustrativă: scenele de categorie sunt ilustrative; fotografiile produselor sunt în meniul electronic.</p></section>
</div></main>
<?= file_get_contents(__DIR__.'/footer.html') ?>
<script src="assets/site.js" defer></script><script src="assets/menu-editorial.js" defer></script>
</body></html>
<?php return (string)ob_get_clean();
}

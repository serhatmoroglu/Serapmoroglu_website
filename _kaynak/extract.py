"""Eski WordPress sayfalarından TÜM içeriği (metin, görsel, link) çıkarır.
Çıktı: database/seeders/data/*.json  +  public/uploads/eski/* (indirilen görseller)"""
import re, json, html, os, unicodedata, urllib.request, ssl
from bs4 import BeautifulSoup
ROOT='_kaynak/eski-site/'; OUT='database/seeders/data/'; UP='public/uploads/eski/'
os.makedirs(UP,exist_ok=True)
MEDIA={}
def media(url):
    """URL'yi yerel yola çevirir; -300x225 gibi küçük sürüm eklerini atıp orijinali dener."""
    if not url: return None
    url=url.replace('http://','https://').split('?')[0]
    if 'placeholder.png' in url or '/themes/' in url or 'Logo' in url or 'logo' in url.lower(): return None
    if url in MEDIA: return MEDIA[url]
    orig=re.sub(r'-(?:e\d+-)?(?:scaled-)?\d{2,4}x\d{2,4}(?=\.\w+$)','',url)
    orig=re.sub(r'-e\d{10,}(?=\.\w+$)','',orig)
    name=os.path.basename(orig)
    path=UP+name
    if not os.path.exists(path):
        ok=False
        for u in dict.fromkeys([orig,url]):
            try:
                req=urllib.request.Request(u,headers={'User-Agent':'Mozilla/5.0'})
                data=urllib.request.urlopen(req,timeout=40).read()
                if len(data)>500: open(path,'wb').write(data); ok=True; break
            except Exception as e: pass
        if not ok: MEDIA[url]=None; print('  ! indirilemedi',url); return None
    MEDIA[url]='uploads/eski/'+name
    return MEDIA[url]
def slugify(t):
    t=t.replace('ı','i').replace('İ','I')
    t=''.join(c for c in unicodedata.normalize('NFKD',t) if not unicodedata.combining(c)).lower()
    return re.sub(r'-+','-',re.sub(r'[^a-z0-9]+','-',t)).strip('-')[:70].rstrip('-')
def zw(t): return re.sub('[​ ]',' ',t).strip()
def main(pid):
    s=open(f'{ROOT}{pid}.html',encoding='utf-8').read()
    return BeautifulSoup(re.search(r'<main.*?</main>',s,re.S).group(0),'html.parser')
def clean(el):
    for t in el.find_all(True):
        if t.name=='img':
            src=media(t.get('src')); 
            if not src: t.decompose(); continue
            t.attrs={'src':'/'+src,'alt':t.get('alt','')}
        else:
            keep=('href',) if t.name=='a' else ()
            for a in list(t.attrs):
                if a not in keep: del t.attrs[a]
    for t in el.find_all(['span','div']): t.unwrap()
    return el
def block_html(el):
    out=[]
    for ch in el.children:
        n=getattr(ch,'name',None)
        if n in ('p','ul','ol','h2','h3','h4','blockquote','figure'):
            if ch.get_text(strip=True) or ch.find('img'): out.append(str(ch))
    return '\n'.join(out)

TREAT=[(1607,'İmplant Tedavisi','cerrahi','Kalıcı diş kökü'),(1620,'Sinüs Lifting Ameliyatı','cerrahi','Üst çene kemik desteği'),
(1622,'İleri İmplant Cerrahisi','cerrahi','Kemik kaybı olan vakalar'),(1613,'Gömük Diş Çekimi','cerrahi','Yirmi yaş dişleri'),
(1628,'Çene Kistleri','cerrahi','Teşhis ve cerrahi'),(1634,'Endodontik Cerrahi','cerrahi','Apikal rezeksiyon'),
(1626,'Lazer Uygulamaları','tedavi','Yumuşak ve sert doku'),(1630,'Bruksizim ve Çene Eklemi','tedavi','Diş sıkma, TME'),
(1632,'Protez Öncesi Cerrahi','protez','Zemin hazırlığı'),(1636,'Diş Protezleri','protez','Sabit ve hareketli'),
(1638,'Laminate Veneer','protez','İnce porselen yüzey'),(1640,'Porselen / Zirkonyum','protez','Dayanıklı kaplama'),
(1642,'Endodonti','tedavi','Kanal tedavisi'),(1644,'Ortodonti','tedavi','Diş ve çene hizalama')]
items=[]
for n,(pid,title,grp,sub) in enumerate(TREAT,1):
    m=main(pid); parts=[]; feat=None
    for w in m.select('[data-widget_type]'):
        t=w['data-widget_type']
        if t.endswith('image.default') or t.startswith('image') or t.startswith('raven-image'):
            i=w.find('img'); src=media(i.get('src')) if i else None
            if src and feat is None: feat=src
            elif src: parts.append(f'<figure><img src="/{src}" alt="{html.escape(title)}"></figure>')
        elif 'heading' in t:
            h=w.find(re.compile('^h[1-6]$'))
            if h and zw(h.get_text()): parts.append(f'<h2>{html.escape(zw(h.get_text(" ",strip=True)))}</h2>')
        elif t.startswith('text-editor'):
            parts.append(block_html(clean(w.select_one('.elementor-widget-container'))))
    body='\n'.join(p for p in parts if p)
    body=re.sub(r'^<h2>'+re.escape(html.escape(title))+r'</h2>\n?','',body)
    items.append(dict(slug=slugify(title),title=title,group=grp,summary=sub,body=body,image=feat,sort=n))
    print('T',pid,title,len(body),feat)
json.dump(items,open(OUT+'treatments.json','w',encoding='utf-8'),ensure_ascii=False,indent=1)

# ---- Yazılarım: liste + tam metin sayfaları ----
FULL=[1898,1882,1927,1997,2009,2021,2034,2043]
posts=[]
for n,pid in enumerate(FULL,1):
    s=open(f'{ROOT}{pid}.html',encoding='utf-8').read()
    soup=BeautifulSoup(s,'html.parser')
    title=zw(html.unescape(re.search(r'<title>(.*?)</title>',s,re.S).group(1)).split('–')[0])
    h1=soup.select_one('main h1, .jupiterx-post-title, h1.entry-title')
    c=soup.select_one('.jupiterx-post-content'); imgs=[i.get('src') for i in c.find_all('img')]
    feat=media(imgs[0]) if imgs else None
    # ilk <p><img></p> öne çıkan görsel olarak alınır, gövdeden çıkar
    first=c.find('p')
    if first and first.find('img') and not first.get_text(strip=True): first.decompose()
    body=block_html(clean(c))
    # listede görünen başlık
    posts.append(dict(kind='yazi',title=title,slug=slugify(title),body=body,excerpt=BeautifulSoup(body,'html.parser').get_text(' ',strip=True)[:220],image=feat,sort=n))
    print('Y',pid,title,len(body),feat)
# listedeki (kısa) başlıkları kullan
lst=main(1854); lt=[zw(w.get_text(' ',strip=True)) for w in lst.select('[data-widget_type="heading.default"]')]
for p,t in zip(posts,lt): p['list_title']=t

# ---- Gazete yazılarım (dış bağlantılı) ----
m=main(2071); news=[];cur=None
for w in m.select('[data-widget_type]'):
    t=w['data-widget_type']
    if t.startswith('image'):
        a=w.find('a'); i=w.find('img'); cur={'kind':'gazete','image':media(i.get('src')) if i else None,'external_url':a['href'] if a else None}; news.append(cur)
    elif t.startswith('heading') and cur is not None and 'title' not in cur:
        cur['title']=zw(w.get_text(' ',strip=True)); cur['slug']=slugify(cur['title'])
    elif t.startswith('text-editor') and cur is not None and 'body' not in cur:
        c=clean(w.select_one('.elementor-widget-container')); cur['body']=block_html(c) or f'<p>{c.get_text(" ",strip=True)}</p>'; cur['excerpt']=c.get_text(' ',strip=True)[:220]
    elif t.startswith('jet-button') and cur is not None:
        a=w.find('a'); 
        if a and a.get('href'): cur['external_url']=a['href']
for n,a in enumerate(news,1): a['sort']=n; print('G',a.get('title'),a.get('external_url'),a.get('image'))
json.dump(posts+news,open(OUT+'posts.json','w',encoding='utf-8'),ensure_ascii=False,indent=1)

# ---- Videolar ----
vids=[]
m=main(2078); cur=None
for w in m.select('[data-widget_type]'):
    t=w['data-widget_type']
    if t.startswith('heading'): cur={'kind':'video','title':zw(w.get_text(' ',strip=True))}; cur['slug']=slugify(cur['title']); vids.append(cur)
    elif t.startswith('image') and cur is not None:
        i=w.find('img'); cur['image']=media(i.get('src')) if i else None
    elif t.startswith('jet-button') and cur is not None:
        a=w.find('a'); cur['video_url']=a['href'].split('?')[0] if a else None
for n,v in enumerate(vids,1): v['sort']=n
json.dump(vids,open(OUT+'videos.json','w',encoding='utf-8'),ensure_ascii=False,indent=1)
print('V',[(v['title'],v.get('video_url')) for v in vids])

# ---- Sayfalar: hakkımda, klinik, anasayfa ----
m=main(8); tx=[zw(w.get_text(' ',strip=True)) for w in m.select('[data-widget_type="text-editor.default"]')]
heads=[zw(w.get_text(' ',strip=True)) for w in m.select('[data-widget_type^="raven-heading"]')]
imgs=[media(i.get('src')) for i in m.find_all('img')]; vid=[v.get('src') for v in m.find_all('video')]
about=dict(headings=heads,bio=tx[0].strip('“”" '),comfort_title=heads[2] if len(heads)>2 else '',comfort=tx[1] if len(tx)>1 else '',images=[i for i in imgs if i],video=[media(v) if False else v for v in vid])
print('ABOUT heads',heads,'imgs',about['images'],'video',vid)
m=main(12); klinik=dict(images=[x for x in (media(i.get('src')) for i in m.find_all('img')) if x],text=zw(m.get_text(' ',strip=True)))
print('KLINIK',klinik['images'])
h=open('home.html' if os.path.exists('home.html') else ROOT+'anasayfa.html',encoding='utf-8').read()
hm=BeautifulSoup(re.search(r'<main.*?</main>',h,re.S).group(0),'html.parser')
home=dict(images=[x for x in (media(i.get('src')) for i in hm.find_all('img')) if x])
print('HOME',home['images'])
json.dump(dict(about=about,klinik=klinik,home=home),open(OUT+'pages.json','w',encoding='utf-8'),ensure_ascii=False,indent=1)
print('Toplam görsel:',len(os.listdir(UP)))

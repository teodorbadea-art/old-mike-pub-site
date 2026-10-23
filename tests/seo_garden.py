"""SEO and live-runtime preservation checks; no network or third-party packages."""
from html.parser import HTMLParser
from pathlib import Path
import hashlib
import json
import re
import unittest

ROOT = Path(__file__).resolve().parents[1]

class Page(HTMLParser):
    def __init__(self, text):
        super().__init__()
        self.tags = []
        self.feed(text)

    def handle_starttag(self, tag, attrs):
        self.tags.append((tag, dict(attrs)))

def contract(text):
    tags = Page(text).tags
    return {
        'assets': [[tag, attrs] for tag, attrs in tags if tag in ('img', 'source', 'link') and (tag != 'link' or attrs.get('rel') not in ('canonical',))],
        'forms': [[tag, attrs] for tag, attrs in tags if tag in ('form', 'input', 'textarea', 'select', 'option')],
        'scripts': re.findall(r'<script(?! type="application/ld\+json")[^>]*>.*?</script>', text, re.S),
        'h1': re.findall(r'<h1[^>]*>.*?</h1>', text, re.S),
        'ids': [attrs['id'] for _, attrs in tags if 'id' in attrs],
        'classes': [attrs['class'] for _, attrs in tags if 'class' in attrs],
    }

class GardenSEO(unittest.TestCase):
    def test_static_pages_and_live_contracts(self):
        baseline = json.loads((ROOT / 'tests/fixtures/garden-seo-baseline.json').read_text())
        titles, descriptions = set(), set()
        for filename, expected in baseline.items():
            with self.subTest(page=filename):
                text = (ROOT / filename).read_text()
                self.assertEqual(contract(text), expected)
                tags = Page(text).tags
                title = re.search(r'<title>(.*?)</title>', text).group(1)
                self.assertIn('Old Mike Garden', title)
                self.assertNotIn('Old Mike Pub', title)
                titles.add(title)
                metas = {a.get('name', a.get('property')): a.get('content') for t, a in tags if t == 'meta'}
                self.assertEqual(metas['robots'], 'index,follow')
                descriptions.add(metas['description'])
                self.assertEqual(metas['og:title'], title)
                self.assertEqual(metas['og:site_name'], 'Old Mike Garden')
                canonical = [a['href'] for t, a in tags if t == 'link' and a.get('rel') == 'canonical']
                url = 'https://garden.oldmike.ro/' + ('' if filename == 'index.html' else filename)
                self.assertEqual(canonical, [url])
                self.assertEqual(metas['og:url'], url)
                self.assertIn('assets/site-runtime.js', text)
                self.assertIn('OLD MIKE PUB SRL', text)
                self.assertNotIn('GARDEN SRL', text)
                self.assertNotIn('PUB · BUCUREȘTI', text)
                for raw in re.findall(r'<script type="application/ld\+json">(.*?)</script>', text, re.S):
                    data = json.loads(raw)
                    self.assertEqual(data['name'], 'Old Mike Garden')
                    self.assertEqual(data['alternateName'], 'Old Mike Pub')
                    if data['@type'] == 'Restaurant':
                        self.assertEqual(data['menu'], 'https://garden.oldmike.ro/meniu.html')
                        self.assertEqual(data['telephone'], '+40787366166')
        self.assertEqual(len(titles), 5)
        self.assertEqual(len(descriptions), 5)

    def test_menu_public_contract(self):
        php = (ROOT / 'meniu.php').read_text()
        self.assertIn("'alternateName'=>'Old Mike Pub'", php)
        self.assertIn('<title>Meniu Old Mike Garden', php)
        self.assertIn("'name'=>'Meniu Old Mike Garden'", php)
        self.assertIn('OLD MIKE PUB SRL', php)
        self.assertIn('https://meniu.oldmike.ro/m/old-mike/loc-oldmike-matei', php)
        self.assertIn('Valori estimate, calculate din rețetă.', php)
        self.assertIn('.form-honeypot{position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden}', (ROOT / 'assets/site.css').read_text())

if __name__ == '__main__':
    unittest.main()

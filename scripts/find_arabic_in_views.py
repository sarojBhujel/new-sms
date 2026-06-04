from pathlib import Path
import re
from collections import defaultdict

root = Path('resources/views')
pattern = re.compile(r'[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF]+(?:[\s\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF]+)*')
results = defaultdict(set)
for path in root.rglob('*.blade.php'):
    text = path.read_text(encoding='utf-8')
    for match in pattern.finditer(text):
        results[match.group(0)].add(str(path))
for phrase, files in sorted(results.items(), key=lambda x: (-len(x[0]), x[0])):
    print(repr(phrase), len(files))
    for f in sorted(files):
        print('  ', f)
    print()
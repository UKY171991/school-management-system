import re
from collections import Counter

def find_duplicates(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # This regex looks for "key": and captures the key
    keys = re.findall(r'"(.*?)"\s*:', content)
    
    counts = Counter(keys)
    for key, count in counts.items():
        if count > 1:
            # Find all line numbers for this key
            lines = content.splitlines()
            line_nums = [i+1 for i, line in enumerate(lines) if f'"{key}"' in line and ':' in line]
            print(f"Duplicate key '{key}' found on lines: {line_nums}")

print("Checking en.json:")
find_duplicates('c:/school management system/lang/en.json')
print("\nChecking hi.json:")
find_duplicates('c:/school management system/lang/hi.json')

import json

def clean_json(file_path):
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            data = json.load(f)
        
        # json.load already handles duplicates by keeping the last one
        
        with open(file_path, 'w', encoding='utf-8') as f:
            json.dump(data, f, ensure_ascii=False, indent=4)
        print(f"Cleaned {file_path}")
    except Exception as e:
        print(f"Error cleaning {file_path}: {e}")

clean_json('c:/school management system/lang/en.json')
clean_json('c:/school management system/lang/hi.json')

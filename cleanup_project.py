import re
from pathlib import Path

root = Path(r'c:\xampp\htdocs\Homme_dOr')
extypes = {'.php', '.js', '.css', '.html', '.htm'}
comment_decoration = re.compile(r'^[ \t]*(//|#|/\*|\*)[ \t]*[-=*_]{2,}[ \t]*(.*?)[ \t]*$')
block_header = re.compile(r'/\*+[ \t]*[-=*_]+[ \t]*(.*?)$')
block_footer = re.compile(r'^(.*?)?[-=*_]+[ \t]*\*+/')
inline_decoration = re.compile(r'^(?P<indent>[ \t]*)(?P<prefix>//|#)(?P<content>.*)$')


def collapse_spaces(line):
    indent = re.match(r'^[ \t]*', line).group(0)
    rest = line[len(indent):]
    out = []
    i = 0
    state = None
    while i < len(rest):
        ch = rest[i]
        if state:
            out.append(ch)
            if ch == state:
                backslashes = 0
                j = i - 1
                while j >= 0 and rest[j] == '\\':
                    backslashes += 1
                    j -= 1
                if backslashes % 2 == 0:
                    state = None
            i += 1
        else:
            if ch in '"`':
                state = ch
                out.append(ch)
                i += 1
            elif ch == ' ':
                j = i
                while j < len(rest) and rest[j] == ' ':
                    j += 1
                out.append(' ')
                i = j
            else:
                out.append(ch)
                i += 1
    return indent + ''.join(out)


converted_files = []
for path in root.rglob('*'):
    if path.is_file() and path.suffix.lower() in extypes:
        text = path.read_text(encoding='utf-8', errors='ignore')
        lines = text.splitlines()
        out_lines = []
        in_block = False
        for line in lines:
            stripped = line.strip()
            if not stripped:
                out_lines.append(line)
                continue
            if comment_decoration.match(line):
                content = comment_decoration.match(line).group(2)
                if not content:
                    continue
                out_lines.append(re.sub(r'([ \t]*)(//|#|\*)([ \t]*[-=*_]+[ \t]*)?(.*)', r'\1\2 \4', line).rstrip())
                continue
            if stripped.startswith('/*'):
                m = block_header.match(stripped)
                if m:
                    content = m.group(1).strip()
                    if content:
                        out_lines.append(re.sub(r'/\*+[ \t]*[-=*_]+[ \t]*', '/* ', line).rstrip())
                    else:
                        out_lines.append('/*')
                    in_block = True
                    continue
            if in_block:
                if stripped.endswith('*/'):
                    m = block_footer.match(stripped)
                    if m:
                        content = m.group(1).strip()
                        if content:
                            out_lines.append(re.sub(r'(.*?)?[-=*_]+[ \t]*\*+/', r'\1 */', line).rstrip())
                        else:
                            out_lines.append('*/')
                    else:
                        out_lines.append(line.rstrip())
                    in_block = False
                    continue
                if re.match(r'^[ \t]*\*[ \t]*[-=*_]{2,}[ \t]*$', line):
                    continue
            m = inline_decoration.match(line)
            if m:
                content = m.group('content').strip()
                content = re.sub(r'^[-=*_]+\s*', '', content)
                content = re.sub(r'\s*[-=*_]+$', '', content)
                out_lines.append(m.group('indent') + m.group('prefix') + ' ' + content)
                continue
            out_lines.append(collapse_spaces(line.rstrip()))
        new_text = '\n'.join(out_lines) + ('\n' if text.endswith('\n') else '')
        if new_text != text:
            path.write_text(new_text, encoding='utf-8')
            converted_files.append(str(path.relative_to(root)))

print('cleaned files:', len(converted_files))
for f in converted_files[:100]:
    print(f)

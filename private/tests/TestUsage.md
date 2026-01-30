# RDML Transpiler — Golden Tests (Batch Runner)

This runner compiles RDML test cases and compares the generated outputs against "golden" expected files.

## Environment
- Windows 11
- QuickJS runtime (no process spawning: `os.system` / `exec` / `spawn`)
- Compilation orchestrated by a `.bat` file

## Where to run
Run from the project root (`rdml-transpiler\`):

```bat
run-tests.bat
```

## Directory layout
- Test cases: `private/tests/cases/<caseName>/`
- Each case folder name is used as the program name.

## Required input per case
For a case named `hello_world`, provide:

```
private/tests/cases/hello_world/hello_world.txt
```

## Produced outputs (for every case)
The runner always produces these files under:

```
private/tests/cases/<caseName>/actual/
  <caseName>.html
  <caseName>.php
  <caseName>.ast
```

## Expected (golden) outputs (for comparisons)
If present, expected files live under:

```
private/tests/cases/<caseName>/expected/
  <caseName>.html
  <caseName>.php
  <caseName>.ast
```

## Command-line parameters
- `--only <caseName>`  
  Runs a single test case by name (folder name).  
  Example:
  ```bat
  run-tests.bat --only hello_world
  ```

- `--update`  
  "Blesses" current compiler output by copying `actual/*` → `expected/*` for each case that compiles successfully.  
  Example:
  ```bat
  run-tests.bat --update
  ```

Flags can be combined:
```bat
run-tests.bat --only hello_world --update
```

## Result statuses
Per-output statuses:
- `PASS` — Expected file exists and matches the actual output exactly.
- `FAIL` — Expected exists but differs, or actual output wasn’t produced.
- `MISSING` — Expected file does not exist (no comparison performed).

Case-level summaries:
- `PASS` — All three expected files exist and all match.
- `PASS+MISSING` — No diffs, but at least one expected file is missing.
- `FAIL` — At least one output differs, an output wasn’t produced, or compilation failed.

Final summary line
```
Summary: pass=<n> pass+missing=<n> fail=<n>
```

## Exit code
- `0` if `fail=0`
- `1` if `fail>0`

## Expected workflow
1. First time (bootstrap) — create case input files, then generate goldens:
   ```bat
   run-tests.bat --update
   ```
2. Normal regression run — compare current compiler output to goldens:
   ```bat
   run-tests.bat
   ```
3. After intentional compiler/codegen changes — regenerate all goldens:
   ```bat
   run-tests.bat --update
   ```
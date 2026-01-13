
### Road Map Items (High Level)
- [x] Basic RDML commands 
- [x] PHP output backend 
- [ ] Samples 
- [ ] RDML extended commands
- [ ] BIFS - built in functions
- [ ] C# backend 
- [ ] Elxir backend 

### Task List

- [X] Implement First Runable Version
    
    This version ships with one simple example (text.txt) which represents very simple RDML code. 

    To compile:
    1. In a dos prompt navigate to the installation folder
    2. Type the following `rdml2php.bat`
    3. When prompted answer `test` then `n`
    4. Compiler should create in the same folder test.php and test.html
    5. This can be executed by moving the following to a directory inside htdocs. There will be runtime warmings about DB setup (this can be ignored). Note because of library support for ext/mysql this will only run on version below PHP 7.0.0.

    ll_library.js
    ll_library.php
    tbs_class.php
    test.php
    test.html

- [X] Upgrade mySQL support from deprecated and discontinued ext/mysql to MySQLi or PDO
- [ ] Guard against SQL injection attacks

- [ ] Verify PHP Support for modern versions of PHP. Current version runs in PHP 5.3.1.
- [ ] Use current JS Engine like QuickJS or Node vs. SpiderMonkey

- [ ] "Fix" paging logic example to not read entire file and get page size from a single query (not great for BIG files)
      (current example reads the entire file to determine # of records) : implement USING clause for a cleaner example

- [ ] Document Supported RDML Commands


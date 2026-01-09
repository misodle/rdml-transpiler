
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
    
    This version ships with one simple example (text.txt) which represents very simple RDML code. It compiles into a primitive and unstyled web based interface which auto generates input and output fields. This is runable by moving to a php runtime. Generated files are text.php and text.html.

- [ ] Verify PHP Support for modern versions of PHP. Current version runs in PHP 5.3.1.
- [ ] Upgrade mySQL support from deprecated and discontinued ext/mysql to MySQLi or PDO
- [ ] Use current JS Engine like QuickJS or Node vs. SpiderMonkey
- [ ] Fix paging logic example to not read entire file and get page size from a single query (not great for BIG files)
- [ ]


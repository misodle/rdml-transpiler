
// shims for spidermonkey vs. quickjs

// import * as std from 'std';

globalThis.load = function(path) {
  std.loadScript(path);
};

globalThis.readline = function() {
  return std.in.getline();
};

globalThis.print = function(...args) {
  std.out.puts(args.join(' ') + '\n');
};

globalThis.read = function(path) {
  return std.loadFile(path);
};

globalThis.debug = function(...args) {
  std.err.puts(args.join(' ') + '\n');
};

globalThis.arguments = scriptArgs.slice(1);   // sript first agrument for difference between spidermonkey and quickjs

// load base ometa libraries
load("./private/ometa/lib.js");
load("./private/ometa/ometa-base.js");
load("./private/ometa/parser.js");
load("./private/ometa/bs-js-compiler.js");
load("./private/ometa/bs-ometa-compiler.js");
load("./private/ometa/bs-ometa-optimizer.js");
load("./private/ometa/bs-ometa-js-compiler.js");

function translateCode(s) {
  var translationError = function(m, i) { alert("Translation error - please tell Alex about this!"); throw fail },
      tree             = BSOMetaJSParser.matchAll(s, "topLevel", undefined, function(m, i) { throw fail.delegated({errorPos: i}) })
  return BSOMetaJSTranslator.match(tree, "trans", undefined, translationError)
}

function ometa(s) { return eval(translateCode(s)) }

// Read Source File - instead of    ==>  var source = read("source.txt");  <==
var source = '';
var std_in = readline();
while (std_in != null) {
	source += std_in + "\n";
	std_in = readline();
}
debug("Finished reading source file");

// Create AST
//var parser = read("./private/rdml/z_parser.txt");	
var parser = std.loadFile('./private/rdml/z_parser.txt');
ometa(parser);
var tree = CalcParser.matchAll(source, 'start');

debug("Finished parsing and building AST");

// Compile to PHP
var theScriptName = arguments[0].toLowerCase();
var compiler = read("./private/rdml/z_compiler_php.txt");

debug("load compiler logic");
ometa(compiler);
debug("compiler logic loaded");
debug("compile code vs. AST");
var code = CalcCompiler.match(tree, 'ast');
debug("right after creating code matching compiler to AST");
print(code);

debug("Compiled Script was " + theScriptName);

 
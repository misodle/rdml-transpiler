
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

// Create AST
var parser = read("./private/rdml/z_parser.txt");	
ometa(parser);
var tree = CalcParser.matchAll(source, 'start');

// print(tree);    -- we could stop here and just print the AST without formatting, instead we build a little compiler implementation to formant the AST for readability

// "Compile" to printable AST
var theScriptName = arguments[0].toLowerCase();
var compiler = read("./private/rdml/z_print_ast.txt");
ometa(compiler);
var code = CalcCompiler.match(tree, 'ast');
print(code);

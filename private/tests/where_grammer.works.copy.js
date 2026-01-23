// import * as std from 'std';
// C:\laragon\www\rdml-transpiler\private\tests>..\quickjs\qjs.exe --std where_grammer.js

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

// load base ometa libraries
load("../ometa/lib.js");
load("../ometa/ometa-base.js");
load("../ometa/parser.js");
load("../ometa/bs-js-compiler.js");
load("../ometa/bs-ometa-compiler.js");
load("../ometa/bs-ometa-optimizer.js");
load("../ometa/bs-ometa-js-compiler.js");

function translateCode(s) {
  var translationError = function(m, i) { alert("Translation error - please tell Alex about this!"); throw fail },
      tree             = BSOMetaJSParser.matchAll(s, "topLevel", undefined, function(m, i) { throw fail.delegated({errorPos: i}) })
  return BSOMetaJSTranslator.match(tree, "trans", undefined, translationError)
}

function ometa(s) { return eval(translateCode(s)) }

// --- inline test grammar ---
var testGrammar = `
ometa CalcParser { 
  	start				= "WHERE(" whereBody:w  ")"			    -> w
						| anything+:xs   						-> {debug(xs.join('')); return 'N/A'},
	whereBody	    	= ( parenExpr | nonParenChar )+:xs      -> xs.join(''),
	parenExpr			= "(" whereBody:w ")" 					-> ("(" + w + ")"),
    nonParenChar  		= nonParenCharChar+:cs                  -> cs.join(''),
    nonParenCharChar 	= anything:c ?(c != '(' && c != ')')	-> c
}  
`;

//var parser = read("test_grammer.txt");
//var src = read("test_source.txt");

// --- parse the grammar into a parser object ---
try {
    ometa(testGrammar);   // uses your existing ometa() function
    debug("Grammar loaded successfully");
} catch(e) {
    debug("Grammar failed to load:", e.message);
}

// --- test driver ---
function test(rule, source) {
    try {
        var tree = CalcParser.matchAll(source, rule);
        debug("OK: source=[", source, "] ast=[", tree, "]");
    } catch(e) {
        debug("FAIL: source=[", source, "] try_err_msg=[", e.message, "]");
    }
}


// --- run tests ---

test("start", "WHERE( (#player_id = 1654) )");
test("start", `WHERE( (#player_id = 1654) )`);


//test("start", `
//  SELECT Fields(*all) From_File(nfl_player_defense) WHERE( #player_id = 1654 ) limit(#start,#limit)
//`);
//test("start", "Select Fields(*all) From_File(nfl_player_defense) WHERE( (#player_id = 1654) ) limit(#start,#limit)");  
//test("start", "where( ( #player_id = 1654 or #player_id = #input1 ) )");
//test("start", "WHERE #player_id = 1654");
//test("start", "WHERE ) #player_id = 1654");
//test("start", "WHERE ( #player_id = 1654");

//test("start", "WHERE(#player_id = 1654)");
//test("start", "WHERE((#player_id = 1654 or #player_id = #input1) OR (#nfl_team_id3 = CHI and #position_id = LB))");
//test("start", "abc");
//test("start", "(abc)");
//test("start", "((a)(b))");
//test("start", "(a(b(c)))");
//test("start", "(abc");   // should fail
	  
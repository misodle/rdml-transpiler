// test.js

// Input string
let w = "#ABC _ABC  ABC 'quoted' \"quoted2\"";

// Regex to wrap unquoted literals but skip ones starting with #
const   regex = /(\s+|^)([^"'#\s][^\s]*)(\s+|$)/g;
//const regex = /(?<=^|\s)([^#"'\s][^\s]*)(?=\s|$)/g;

// Apply the regex
w = w.replace(regex, '$1"$2"$3');
//w = w.replace(regex, '"$1"');

// Output the result
print("Processed string:", w);

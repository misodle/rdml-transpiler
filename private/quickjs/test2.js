// test.js

// Input string examples
let examples = [
    "#ABC _ABC ABC 'quoted' \"quoted2\"",
    "  #SKIP start _wrap middle ABC 'already' ",
    "_onlyWrap _123 _$special #ignore",
    "#startAtHash _middle 'quoted' \"double\" end"
];

// Regex to wrap unquoted literals but skip ones starting with #
const regex = /(\s*|^)(?!#)([^"' \s][^ \s]*)(\s*|$)/g;

// Test each example
examples.forEach((str, index) => {
    const result = str.replace(regex, '$1"$2"$3');
    console.log(`Example ${index + 1}:`);
    console.log("Original: ", str);
    console.log("Processed:", result);
    console.log('-------------------------');
});

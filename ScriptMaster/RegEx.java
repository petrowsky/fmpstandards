/**
 * =====================================
 * RegEx ( text ; pattern )
 *
 * PURPOSE:
 * Runs Regular expression on a sting of text.
 *
 * RETURNS:
 * A return delimited list of all found text from the pattern
 *
 * EXAMPLE:
 * RegEx ( "Stargate Universe E01S05"; "e\d{1,3}s(\d{1,3})" )
 * The example will find the season number. of "05"
 *
 * For more info about Regular Expression try this app. http://gskinner.com/RegExr/desktop/
 */


import java.util.regex.*;String regex = pattern;
Pattern pattern = Pattern.compile(regex, Pattern.CASE_INSENSITIVE | Pattern.MULTILINE | Pattern.DOTALL);
Matcher matcher = pattern.matcher(text);
List result = new LinkedList();
while (matcher.find()) { result.add( matcher.group(1) );}
return result;
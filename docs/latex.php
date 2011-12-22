<?php

function escape($str) {
	$str = strtr($str, array(
		'{' => '\{',
		'}' => '\}',
		'_' => '\_',
		'%' => '\%',
		'\\textbackslash{}' => '\\textbackslash{}\-'
	));
	return str_replace('\{\}', '{}', $str);
}

$code = array();

$transform = array(
	'~/--+(\w*)(.*?)\\\\--+~s' => function ($m) {
		global $code;
		$i = count($code);
		$code[] = array($m[1], $m[2]);
		return 'CODE' . $i;
	},

	// zpětná lomítka
	'~\\\\(?!-)~' => '\\textbackslash{}',

	'~----+~' => '',

	'~\\.\[(\w+)\]\r?\n?(.*)(\r?\n\r?\n)~sU' => '\begin{$1}$2'. "\r\n" .'\end{$1}$3',

	// nadpisy
	'~([^\n\r]+)\r?\n#+~' => '\section*{$1}',
	'~([^\n\r]+)\r?\n\*+~' => '\subsection*{$1}',
	'~([^\n\r]+)\r?\n=+~' => '\subsubsection*{$1}',

	'~\\[\\*\s*(\S*)\s*\\*\\]~' => '\begin{figure}\begin{center}\includegraphics[scale=0.5]{media/$1}\end{center}\end{figure}',

	// inline kód
	'~`([^`]+?)`~' => function($m) {
		return '\texttt{' . escape($m[1]) . '}';
	},

	'~\\$~' => '\\\\$',

	// formátování textu
	'~\*\*(.*?)\*\*~' => '\textbf{$1}',
	'~\*(.*?)\*~' => '\textit{$1}',

	// speciální znaky
	'~&~' => '\&',

	'~(\r?\n\r?\n)(?=-\s)~' => '$1\begin{my_item}' . "\n",
	'~(?<=\n)(-\s.*)(\r?\n\r?\n)~' => '$1\end{my_item}$2',
	'~(?<=\n)-\s~' => '\item ',

	'~"(.*)":\[(.*)\]~U' => '\href{$2}{$1}',

	// bloky kódu

	//'~/--+(\s*\n.*?)\\\\--+~s' => '\begin{verbatim}$1\end{verbatim}',

	'~CODE(\d+)~' => function ($m) {
		global $code;

		if ($code[$m[1]][0]) {
			return '\begin{minted}[startinline=true]{' . $code[$m[1]][0] . '}' . str_replace('\$', '$', $code[$m[1]][1]) . '\end{minted}';
		} else {
			return '\begin{verbatim}' . $code[$m[1]][1] . '\end{verbatim}';
		}
	}
);

foreach (glob('*.texy') as $file) {
	echo $file . '... ';
	$content = file_get_contents($file);
	foreach ($transform as $pattern => $replacement) {
		if (is_callable($replacement))
			$content = preg_replace_callback($pattern, $replacement, $content);
		else
			$content = preg_replace($pattern, $replacement, $content);
	}
	file_put_contents(str_replace('.texy', '.tex', $file), $content);
	echo "ok\n";
}



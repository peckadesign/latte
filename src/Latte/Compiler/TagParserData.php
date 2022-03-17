<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Compiler\Nodes\Php as Node;
use Latte\Compiler\Nodes\Php\Expr;
use Latte\Compiler\Nodes\Php\Scalar;


/** @internal generated trait used by TagParser */
abstract class TagParserData
{
	/** Symbol number of error recovery token */
	protected const ErrorSymbol = 1;

	/** Action number signifying default action */
	protected const DefaultAction = -8190;

	/** Rule number signifying that an unexpected token was encountered */
	protected const UnexpectedTokenRule = 8191;

	protected const Yy2Tblstate = 247;

	/** Number of non-leaf states */
	protected const NumNonLeafStates = 339;

	/** Map of lexer tokens to internal symbols */
	protected const TokenToSymbol = [
		0,     113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   48,    108,   113,   109,   47,    113,   113,
		99,    100,   45,    42,    2,     43,    44,    46,    113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   22,    103,
		35,    7,     37,    21,    59,    113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   61,    113,   107,   27,    113,   113,   98,    113,   113,
		113,   96,    105,   113,   113,   113,   113,   113,   113,   97,    106,   113,   113,   113,   113,   113,   104,   113,   113,   113,
		113,   113,   113,   101,   26,    102,   50,    113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   1,     3,     4,     5,
		6,     8,     9,     10,    11,    12,    13,    14,    15,    16,    17,    18,    19,    20,    23,    24,    25,    28,    29,    30,
		31,    32,    33,    34,    36,    38,    39,    40,    41,    49,    51,    52,    53,    54,    55,    56,    57,    58,    60,    62,
		63,    64,    65,    66,    67,    68,    69,    70,    71,    72,    73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
		83,    84,    85,    86,    87,    88,    89,    90,    110,   91,    92,    93,    94,    95,    111,   112,
	];

	/** Map of states to a displacement into the self::Action table. The corresponding action for this
	 *  state/symbol pair is self::Action[self::ActionBase[$state] + $symbol]. If self::ActionBase[$state] is 0, the
	 *  action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionBase = [
		317,   139,   139,   139,   98,    139,   139,   219,   219,   219,   141,   141,   141,   301,   301,   282,   292,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   96,
		251,   349,   351,   350,   352,   361,   362,   363,   364,   370,   51,    51,    51,    51,    51,    51,    51,    51,    51,    51,
		51,    51,    51,    51,    38,    32,    250,   105,   105,   105,   105,   105,   105,   105,   105,   105,   105,   105,   105,   105,
		105,   105,   105,   105,   105,   388,   388,   388,   205,   355,   347,   225,   -51,   246,   246,   125,   125,   125,   125,   125,
		260,   260,   260,   260,   258,   258,   258,   258,   258,   258,   258,   258,   77,    77,    77,    77,    25,    25,    120,   112,
		112,   112,   274,   274,   274,   241,   101,   39,    39,    39,    39,    39,    -22,   104,   222,   320,   -61,   -61,   -61,   -61,
		-61,   -61,   321,   371,   264,   300,   300,   304,   91,    91,    91,    300,   331,   92,    -41,   197,   316,   323,   311,   45,
		289,   288,   348,   262,   278,   285,   141,   313,   313,   141,   141,   141,   47,    138,   138,   138,   47,    47,    47,    100,
		295,   162,   6,     353,   295,   295,   295,   167,   -74,   332,   305,   332,   332,   97,    68,    332,   332,   332,   332,   102,
		68,    68,    325,   310,   307,   133,   71,    307,   286,   286,   129,   4,     335,   334,   336,   333,   330,   354,   218,   249,
		322,   283,   329,   306,   244,   335,   334,   336,   280,   218,   63,    293,   293,   293,   327,   293,   293,   293,   293,   293,
		293,   293,   366,   326,   337,   338,   342,   343,   319,   287,   357,   293,   298,   344,   324,   318,   367,   218,   328,   368,
		309,   340,   315,   314,   341,   299,   369,   345,   358,   186,   339,   221,   359,   240,   356,   252,   346,   365,   360,   0,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     51,    51,
		51,    51,    51,    51,    51,    51,    51,    51,    51,    0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,
		51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    0,     260,   51,    51,    51,    51,    51,    51,    51,    0,
		0,     0,     0,     39,    39,    39,    39,    39,    39,    39,    39,    91,    91,    91,    91,    39,    39,    39,    39,    39,
		39,    91,    91,    91,    39,    0,     0,     0,     0,     0,     0,     0,     0,     0,     331,   286,   286,   286,   286,   286,
		286,   331,   331,   0,     0,     0,     0,     0,     0,     0,     0,     0,     331,   286,   0,     0,     0,     0,     0,     0,
		0,     141,   141,   141,   331,   0,     286,   286,   0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     293,
		0,     0,     63,    293,   293,   293,
	];

	/** Table of actions. Indexed according to self::ActionBase comment. */
	protected const Action = [
		23,    24,    325,   21,    0,     363,   25,    364,   26,    164,   165,   27,    28,    29,    30,    31,    32,    33,    365,   2,
		179,   34,    529,   530,   192,   608,   508,   365,   494,   609,   527,   272,   163,   229,   230,   -8190, -8190, 273,   274,   -207,
		89,    -8190, 275,   276,   195,   -67,   187,   -67,   277,   7,     88,    510,   509,   511,   43,    44,    45,    18,    277,   -207,
		-207,  -207,  519,   520,   521,   6,     212,   277,   -167,  8,     9,     -67,   16,    14,    46,    47,    48,    -167,  49,    50,
		51,    52,    53,    54,    55,    56,    57,    58,    59,    60,    61,    62,    63,    64,    65,    66,    67,    68,    69,    90,
		181,   346,   347,   345,   178,   506,   477,   508,   -8190, -8190, -8190, 70,    -8191, -8191, -8191, -8191, 61,    62,    63,    64,
		65,    66,    162,   -21,   390,   -41,   344,   343,   -8190, -8190, -8190, 222,   510,   509,   511,   367,   67,    68,    69,    356,
		181,   187,   346,   347,   345,   -67,   -8190, 350,   -8190, -8190, -8190, 70,    -8190, -8190, -8190, -8191, -8191, -8191, -8191, -8191,
		180,   35,    -207,  -8190, 186,   -203,  284,   344,   343,   344,   343,   285,   352,   223,   224,   351,   357,   286,   287,   535,
		356,   365,   -207,  -207,  -207,  -203,  -203,  -203,  350,   87,    -168,  -167,  95,    188,   -203,  189,   194,   -8190, 399,   -168,
		-167,  180,   35,    -210,  363,   186,   364,   284,   -8190, -8190, -8190, 39,    285,   352,   223,   224,   351,   357,   286,   287,
		-28,   277,   346,   347,   345,   84,    -8190, 96,    -8190, -8190, 36,    37,    10,    71,    72,    73,    74,    75,    76,    77,
		78,    79,    80,    81,    82,    83,    97,    344,   343,   -8190, -8190, -8190, -167,  -8190, -8190, -8190, 85,    11,    -206,  98,
		356,   -167,  187,   346,   347,   345,   -22,   -8190, 350,   -8190, -8190, -8190, 92,    -8190, -8190, -8190, 440,   442,   -206,  -206,
		-206,  180,   35,    -202,  -16,   186,   -201,  284,   -250,  -247,  -250,  -247,  285,   352,   223,   224,   351,   357,   286,   287,
		-15,   356,   12,    -202,  -202,  -202,  -201,  -201,  -201,  350,   94,    -244,  -202,  -244,  86,    -201,  64,    65,    66,    2,
		93,    -209,  349,   348,   241,   -204,  360,   365,   361,   185,   527,   160,   161,   353,   352,   355,   354,   351,   357,   358,
		359,   190,   191,   276,   181,   -204,  -204,  -204,  91,    308,   -8190, -8190, -8190, 70,    -204,  365,   -8190, 38,    -8190, -8190,
		-8190, -201,  519,   520,   521,   20,    212,   277,   -8190, 610,   -8190, -8190, -8190, -177,  -8190, 265,   -8190, 19,    -8190, -8190,
		-8190, -201,  -201,  -201,  214,   199,   200,   201,   -250,  -247,  -201,  -8190, -8190, -8190, 228,   -250,  -247,  196,   197,   198,
		365,   371,   597,   330,   233,   234,   235,   536,   537,   -8190, 13,    -244,  152,   22,    250,   1,     227,   -235,  -244,  15,
		167,   605,   277,   -233,  0,     0,     -210,  -209,  -208,  3,     0,     4,     5,     17,    40,    41,    176,   177,   226,   0,
		262,   264,   482,   525,   401,   400,   497,   0,     -28,   318,   320,   514,   483,   578,   0,     42,    0,     248,   0,     607,
		372,   606,   493,   604,   562,   573,   576,   0,     338,   0,     0,     0,     0,     524,   551,   566,   600,   332,   0,     528,
	];

	/** Table indexed analogously to self::Action. If self::ActionCheck[self::ActionBase[$state] + $symbol] != $symbol
	 *  then the action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionCheck = [
		42,    43,    43,    77,    0,     66,    48,    68,    50,    51,    52,    53,    54,    55,    56,    57,    58,    59,    69,    61,
		62,    63,    64,    65,    66,    66,    68,    69,    102,   70,    72,    73,    26,    75,    76,    3,     4,     79,    80,    61,
		101,   3,     84,    85,    86,    0,     21,    2,     109,   2,     101,   93,    94,    95,    3,     4,     5,     99,    109,   81,
		82,    83,    104,   105,   106,   2,     108,   109,   90,    22,    2,     26,    21,    2,     23,    24,    25,    99,    27,    28,
		29,    30,    31,    32,    33,    34,    35,    36,    37,    38,    39,    40,    41,    42,    43,    44,    45,    46,    47,    2,
		49,    3,     4,     5,     2,     66,    100,   68,    3,     4,     5,     60,    35,    36,    37,    38,    39,    40,    41,    42,
		43,    44,    26,    22,    85,    100,   28,    29,    3,     4,     5,     2,     93,    94,    95,    2,     45,    46,    47,    41,
		49,    21,    3,     4,     5,     100,   21,    49,    23,    24,    25,    60,    27,    28,    29,    30,    31,    32,    33,    34,
		62,    63,    61,    71,    66,    61,    68,    28,    29,    28,    29,    73,    74,    75,    76,    77,    78,    79,    80,    87,
		41,    69,    81,    82,    83,    81,    82,    83,    49,    91,    90,    90,    6,     26,    90,    28,    100,   85,    100,   99,
		99,    62,    63,    99,    66,    66,    68,    68,    3,     4,     5,     99,    73,    74,    75,    76,    77,    78,    79,    80,
		100,   109,   3,     4,     5,     7,     21,    6,     23,    24,    91,    92,    7,     8,     9,     10,    11,    12,    13,    14,
		15,    16,    17,    18,    19,    20,    6,     28,    29,    3,     4,     5,     90,    3,     4,     5,     7,     6,     61,    7,
		41,    99,    21,    3,     4,     5,     22,    21,    49,    23,    24,    25,    22,    27,    28,    29,    51,    52,    81,    82,
		83,    62,    63,    61,    22,    66,    61,    68,    0,     0,     2,     2,     73,    74,    75,    76,    77,    78,    79,    80,
		22,    41,    22,    81,    82,    83,    81,    82,    83,    49,    91,    0,     90,    2,     22,    90,    42,    43,    44,    61,
		22,    99,    62,    63,    66,    61,    66,    69,    68,    22,    72,    26,    26,    73,    74,    75,    76,    77,    78,    79,
		80,    26,    28,    85,    49,    81,    82,    83,    61,    67,    3,     4,     5,     60,    90,    69,    71,    99,    3,     4,
		5,     61,    104,   105,   106,   61,    108,   109,   21,    70,    23,    24,    25,    90,    27,    74,    21,    61,    23,    24,
		25,    81,    82,    83,    61,    81,    82,    83,    100,   100,   90,    3,     4,     5,     90,    107,   107,   81,    82,    83,
		69,    91,    71,    78,    81,    82,    83,    87,    87,    21,    101,   100,   90,    96,    97,    98,    90,    99,    107,   88,
		89,    102,   109,   99,    -1,    -1,    99,    99,    99,    99,    -1,    99,    99,    99,    99,    99,    99,    99,    99,    -1,
		100,   100,   100,   100,   100,   100,   100,   -1,    100,   100,   100,   100,   100,   100,   -1,    101,   -1,    101,   -1,    102,
		102,   102,   102,   102,   102,   102,   102,   -1,    103,   -1,    -1,    -1,    -1,    107,   107,   107,   107,   107,   -1,    108,
	];

	/** Map of states to their default action */
	protected const ActionDefault = [
		8191,  253,   253,   253,   8191,  253,   253,   8191,  8191,  28,    8191,  8191,  8191,  34,    28,    8191,  8191,  8191,  8191,  199,
		199,   199,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  9,     8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  63,    8191,  8191,  28,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		243,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  1,     251,   252,   72,    66,    200,   246,   249,   68,    71,
		69,    38,    39,    45,    107,   109,   141,   108,   83,    88,    89,    90,    91,    92,    93,    94,    95,    96,    97,    98,
		99,    100,   81,    82,    153,   142,   140,   139,   105,   106,   112,   80,    8191,  110,   111,   129,   130,   127,   128,   131,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  132,   133,   134,   135,   56,    56,    56,    8191,
		10,    8191,  119,   120,   122,   8191,  193,   8191,  8191,  8191,  8191,  8191,  193,   192,   137,   8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  195,   102,   104,   174,   114,   115,   113,   84,    8191,  8191,  8191,  194,   8191,  260,   201,   201,
		201,   201,   29,    29,    29,    8191,  29,    8191,  8191,  29,    29,    29,    77,    8191,  8191,  8191,  77,    77,    77,    183,
		125,   207,   8191,  8191,  116,   117,   118,   46,    8191,  178,   8191,  166,   8191,  27,    27,    8191,  219,   220,   221,   27,
		27,    27,    157,   31,    58,    27,    27,    58,    8191,  8191,  27,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  187,
		8191,  205,   217,   2,     169,   14,    19,    20,    8191,  245,   240,   123,   124,   126,   203,   145,   146,   147,   148,   149,
		150,   151,   8191,  173,   8191,  8191,  8191,  8191,  259,   8191,  201,   121,   8191,  8191,  184,   224,   8191,  248,   202,   8191,
		8191,  8191,  48,    49,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  44,    8191,  8191,  8191,
	];

	/** Map of non-terminals to a displacement into the self::Goto table. The corresponding goto state for this
	 *  non-terminal/state pair is self::Goto[self::GotoBase[$nonTerminal] + $state] (unless defaulted) */
	protected const GotoBase = [
		0,     0,     -4,    0,     -1,    144,   0,     128,   -121,  -38,   -33,   19,    0,     0,     0,     0,     124,   168,   -16,   0,
		42,    0,     51,    64,    0,     0,     -80,   -22,   -17,   126,   0,     117,   13,    0,     0,     -14,   157,   24,    0,     44,
		0,     0,     146,   0,     0,     0,     20,    0,     0,     0,     0,     -77,   -60,   0,     0,     32,    40,    48,    17,    -26,
		0,     0,     -57,   59,    0,     -55,   101,   109,   49,    0,     0,
	];

	/** Table of states to goto after reduction. Indexed according to self::GotoBase comment. */
	protected const Goto = [
		114,   302,   307,   114,   114,   114,   128,   116,   117,   113,   113,   105,   126,   113,   99,    115,   115,   115,   110,   291,
		292,   240,   293,   295,   296,   297,   298,   299,   300,   301,   426,   426,   111,   112,   101,   102,   103,   104,   106,   124,
		125,   127,   145,   148,   149,   150,   153,   154,   155,   156,   157,   158,   159,   172,   173,   174,   175,   182,   183,   184,
		208,   209,   210,   244,   245,   246,   311,   129,   130,   131,   132,   133,   134,   135,   136,   137,   138,   139,   140,   141,
		142,   143,   146,   118,   107,   108,   119,   109,   147,   120,   118,   121,   144,   122,   123,   315,   389,   389,   389,   505,
		505,   505,   303,   303,   303,   389,   243,   389,   389,   389,   389,   389,   601,   602,   603,   580,   507,   507,   507,   507,
		507,   507,   565,   565,   565,   507,   404,   507,   507,   507,   507,   507,   312,   260,   261,   312,   312,   312,   376,   577,
		577,   577,   577,   577,   577,   166,   166,   166,   168,   166,   166,   168,   168,   168,   169,   170,   171,   217,   289,   289,
		289,   324,   289,   289,   217,   217,   319,   337,   317,   213,   563,   563,   570,   571,   217,   217,   611,   205,   206,   218,
		310,   219,   211,   220,   221,   306,   225,   217,   526,   526,   526,   526,   526,   526,   526,   526,   545,   545,   545,   545,
		545,   545,   545,   545,   543,   543,   543,   543,   543,   543,   543,   543,   294,   294,   294,   294,   294,   294,   294,   294,
		382,   327,   415,   412,   413,   475,   379,   418,   417,   203,   335,   501,   333,   374,   502,   503,   398,   498,   504,   553,
		554,   555,   304,   305,   595,   334,   499,   0,     304,   305,   263,   386,   391,   393,   392,   394,   257,   258,   567,   568,
		569,   595,   596,   0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     596,   0,     0,     0,     0,     0,
		314,   0,     0,     0,     0,     0,     0,     0,     232,   236,   237,   238,
	];

	/** Table indexed analogously to self::Goto. If self::GotoCheck[self::GotoBase[$nonTerminal] + $state] != $nonTerminal
	 *  then the goto state is defaulted, i.e. self::GotoDefault[$nonTerminal] should be used. */
	protected const GotoCheck = [
		2,     4,     4,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     62,    26,    26,    26,    26,
		26,    26,    51,    51,    51,    26,    66,    26,    26,    26,    26,    26,    8,     8,     8,     67,    52,    52,    52,    52,
		52,    52,    62,    62,    62,    52,    31,    52,    52,    52,    52,    52,    7,     29,    29,    7,     7,     7,     16,    62,
		62,    62,    62,    62,    62,    5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     9,     36,    36,
		36,    18,    36,    36,    9,     9,     42,    42,    36,    59,    62,    62,    65,    65,    9,     9,     9,     32,    32,    32,
		32,    32,    32,    32,    32,    17,    59,    9,     37,    37,    37,    37,    37,    37,    37,    37,    55,    55,    55,    55,
		55,    55,    55,    55,    56,    56,    56,    56,    56,    56,    56,    56,    57,    57,    57,    57,    57,    57,    57,    57,
		20,    10,    35,    35,    35,    39,    10,    10,    10,    58,    9,     9,     10,    10,    28,    28,    22,    10,    28,    28,
		28,    28,    11,    11,    68,    27,    46,    -1,    11,    11,    11,    23,    23,    23,    23,    23,    63,    63,    63,    63,
		63,    68,    68,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    68,    -1,    -1,    -1,    -1,    -1,
		7,     -1,    -1,    -1,    -1,    -1,    -1,    -1,    7,     7,     7,     7,
	];

	/** Map of non-terminals to the default state to goto after their reduction */
	protected const GotoDefault = [
		-8192, 271,   100,   283,   342,   369,   362,   288,   575,   561,   409,   269,   268,   425,   328,   266,   375,   329,   321,   259,
		381,   231,   396,   247,   322,   323,   251,   331,   518,   254,   313,   403,   151,   253,   242,   414,   278,   279,   424,   249,
		491,   267,   316,   495,   336,   270,   500,   552,   252,   280,   255,   515,   239,   207,   281,   215,   204,   193,   202,   594,
		216,   282,   550,   256,   557,   564,   290,   581,   593,   309,   326,
	];

	/** Map of rules to the non-terminal on their left-hand side, i.e. the non-terminal to use for
	 *  determining the state to goto after reduction. */
	protected const RuleToNonTerminal = [
		0,     1,     1,     1,     5,     5,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,
		6,     7,     7,     7,     8,     8,     9,     10,    10,    11,    11,    12,    12,    13,    14,    14,    15,    15,    16,    16,
		18,    18,    19,    19,    20,    20,    22,    22,    22,    22,    23,    23,    24,    24,    25,    25,    21,    21,    27,    27,
		28,    28,    28,    30,    29,    29,    31,    31,    31,    31,    17,    33,    33,    34,    34,    3,     3,     35,    35,    35,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     38,    41,    41,    44,
		45,    45,    46,    47,    47,    47,    51,    26,    26,    52,    52,    52,    52,    39,    39,    39,    49,    49,    43,    43,
		55,    55,    55,    55,    56,    37,    57,    57,    57,    57,    40,    40,    40,    40,    40,    40,    40,    40,    40,    42,
		42,    54,    54,    54,    54,    60,    60,    60,    48,    48,    48,    61,    61,    61,    61,    61,    61,    32,    32,    32,
		32,    32,    62,    62,    65,    64,    53,    53,    53,    53,    53,    53,    53,    50,    50,    50,    63,    63,    63,    36,
		4,     66,    66,    67,    67,    67,    67,    67,    67,    67,    67,    67,    67,    67,    58,    58,    58,    58,    59,    69,
		68,    68,    68,    68,    68,    68,    68,    68,    68,    70,    70,    70,    70,
	];

	/** Map of rules to the length of their right-hand side, which is the number of elements that have to
	 *  be popped from the stack(s) on reduction. */
	protected const RuleToLength = [
		1,     2,     2,     2,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,
		1,     1,     1,     1,     1,     1,     1,     0,     1,     0,     1,     0,     1,     7,     0,     2,     1,     3,     3,     4,
		2,     0,     1,     3,     4,     6,     1,     2,     1,     1,     1,     1,     3,     3,     3,     3,     0,     1,     0,     2,
		2,     4,     3,     1,     1,     3,     1,     2,     2,     3,     2,     3,     1,     4,     4,     3,     4,     0,     3,     3,
		1,     3,     3,     3,     4,     1,     1,     2,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
		3,     2,     2,     2,     2,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
		3,     3,     3,     2,     2,     2,     2,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,     5,
		4,     3,     3,     4,     4,     2,     2,     2,     2,     2,     2,     2,     1,     8,     12,    9,     3,     0,     4,     2,
		1,     3,     2,     2,     2,     4,     1,     1,     1,     1,     1,     1,     1,     1,     1,     3,     1,     1,     0,     1,
		1,     1,     1,     1,     3,     3,     4,     1,     1,     3,     1,     1,     1,     1,     1,     1,     3,     2,     3,     0,
		1,     1,     3,     1,     1,     1,     1,     1,     1,     3,     1,     1,     4,     1,     4,     4,     4,     1,     1,     3,
		3,     3,     1,     4,     1,     3,     1,     4,     3,     3,     3,     3,     3,     1,     3,     1,     1,     3,     1,     4,
		1,     3,     1,     1,     2,     1,     3,     4,     3,     3,     4,     2,     2,     0,     2,     2,     1,     2,     1,     1,
		1,     4,     3,     3,     3,     3,     3,     6,     3,     1,     1,     2,     1,
	];

	/** Map of symbols to their names */
	protected const SymbolToName = [
		'end',
		'error',
		"','",
		"'or'",
		"'xor'",
		"'and'",
		"'=>'",
		"'='",
		"'+='",
		"'-='",
		"'*='",
		"'/='",
		"'.='",
		"'%='",
		"'&='",
		"'|='",
		"'^='",
		"'<<='",
		"'>>='",
		"'**='",
		"'??='",
		"'?'",
		"':'",
		"'??'",
		"'||'",
		"'&&'",
		"'|'",
		"'^'",
		"'&'",
		"'&'",
		"'=='",
		"'!='",
		"'==='",
		"'!=='",
		"'<=>'",
		"'<'",
		"'<='",
		"'>'",
		"'>='",
		"'<<'",
		"'>>'",
		"'in'",
		"'+'",
		"'-'",
		"'.'",
		"'*'",
		"'/'",
		"'%'",
		"'!'",
		"'instanceof'",
		"'~'",
		"'++'",
		"'--'",
		"'(int)'",
		"'(float'",
		"'(string)'",
		"'(array)'",
		"'(object)'",
		"'(bool)'",
		"'@'",
		"'**'",
		"'['",
		"'new'",
		"'clone'",
		'integer',
		'floating-point number',
		'identifier',
		'variable name',
		'constant',
		'variable',
		'number',
		'string content',
		'quoted string',
		"'match'",
		"'default'",
		"'function'",
		"'fn'",
		"'return'",
		"'use'",
		"'isset'",
		"'empty'",
		"'->'",
		"'?->'",
		"'??->'",
		"'list'",
		"'array'",
		'heredoc start',
		'heredoc end',
		"'\${'",
		"'{\$'",
		"'::'",
		"'...'",
		"'(expand)'",
		'fully qualified name',
		'namespaced name',
		'namespace-relative name',
		"'e'",
		"'m'",
		"'a'",
		"'('",
		"')'",
		"'{'",
		"'}'",
		"';'",
		"'true'",
		"'false'",
		"'null'",
		"']'",
		"'\"'",
		"'$'",
		"'\\\\'",
		'whitespace',
		'comment',
	];

	/** Temporary value containing the result of last semantic action (reduction) */
	protected mixed $semValue = null;

	/** Semantic value stack (contains values of tokens and semantic action results) */
	protected array $semStack;

	/** @var Token[] Start attribute stack */
	protected array $startTokenStack;


	protected function reduce(int $rule, int $pos): void
	{
		(match ($rule) {
			0, 1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 27, 28, 46, 57, 59, 80, 85, 86, 152, 166, 168, 173, 174, 176, 177, 179, 187, 192, 194, 195, 200, 201, 203, 204, 205, 206, 208, 210, 211, 213, 217, 218, 222, 226, 233, 235, 236, 238, 260, 272 => fn() => $this->semValue = $this->semStack[$pos],
			3 => fn() => $this->semValue = new Expr\ArrayNode($this->semStack[$pos]),
			21, 22, 23, 24, 25 => fn() => $this->semValue = new Node\IdentifierNode($this->semStack[$pos], $this->startTokenStack[$pos]->line),
			26 => fn() => $this->semValue = new Expr\VariableNode(substr($this->semStack[$pos], 1), $this->startTokenStack[$pos]->line),
			29, 31 => fn() => $this->semValue = false,
			30, 32 => fn() => $this->semValue = true,
			33 => fn() => $this->semValue = new Expr\MatchNode($this->semStack[$pos - 4], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 6]->line),
			34, 41, 60, 77, 157, 178 => fn() => $this->semValue = [],
			35, 40, 70, 78, 79, 137, 138, 158, 159, 175, 202, 209, 234, 237, 268 => fn() => $this->semValue = $this->semStack[$pos - 1],
			36, 42, 64, 72, 160, 242, 256 => fn() => $this->semValue = [$this->semStack[$pos]],
			37, 43, 53, 55, 65, 71, 161, 241 => function () use ($pos) {
				$this->semStack[$pos - 2][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 2];
			},
			38 => fn() => $this->semValue = new Node\MatchArmNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			39 => fn() => $this->semValue = new Node\MatchArmNode(null, $this->semStack[$pos], $this->startTokenStack[$pos - 3]->line),
			44 => fn() => $this->semValue = new Node\ParamNode($this->semStack[$pos], null, $this->semStack[$pos - 3], $this->semStack[$pos - 2], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->line),
			45 => fn() => $this->semValue = new Node\ParamNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->semStack[$pos - 5], $this->semStack[$pos - 4], $this->semStack[$pos - 3], $this->startTokenStack[$pos - 5]->line),
			47 => fn() => $this->semValue = new Node\NullableTypeNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->line),
			48 => fn() => $this->semValue = new Node\UnionTypeNode($this->semStack[$pos], $this->startTokenStack[$pos]->line),
			49 => fn() => $this->semValue = new Node\IntersectionTypeNode($this->semStack[$pos], $this->startTokenStack[$pos]->line),
			50 => fn() => $this->semValue = $this->handleBuiltinTypes($this->semStack[$pos]),
			51 => fn() => $this->semValue = new Node\IdentifierNode('array', $this->startTokenStack[$pos]->line),
			52, 54 => fn() => $this->semValue = [$this->semStack[$pos - 2], $this->semStack[$pos]],
			56, 58, 199, 253 => fn() => $this->semValue = null,
			61 => fn() => $this->semValue = $this->semStack[$pos - 2],
			62 => fn() => $this->semValue = [$this->semStack[$pos - 1]],
			63 => fn() => $this->semValue = new Node\VariadicPlaceholderNode($this->startTokenStack[$pos]->line),
			66 => fn() => $this->semValue = new Node\ArgNode($this->semStack[$pos], false, false, $this->startTokenStack[$pos]->line),
			67 => fn() => $this->semValue = new Node\ArgNode($this->semStack[$pos], true, false, $this->startTokenStack[$pos - 1]->line),
			68 => fn() => $this->semValue = new Node\ArgNode($this->semStack[$pos], false, true, $this->startTokenStack[$pos - 1]->line),
			69 => fn() => $this->semValue = new Node\ArgNode($this->semStack[$pos], false, false, $this->startTokenStack[$pos - 2]->line, $this->semStack[$pos - 2]),
			73, 74, 76 => fn() => $this->semValue = new Expr\FilterNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->line),
			75 => fn() => $this->semValue = new Expr\FilterNode(null, $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			81, 82, 83 => fn() => $this->semValue = new Expr\AssignNode($this->semStack[$pos - 2], $this->semStack[$pos], false, $this->startTokenStack[$pos - 2]->line),
			84 => fn() => $this->semValue = new Expr\AssignNode($this->semStack[$pos - 3], $this->semStack[$pos], true, $this->startTokenStack[$pos - 3]->line),
			87 => fn() => $this->semValue = new Expr\CloneNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->line),
			88 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '+', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			89 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '-', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			90 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '*', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			91 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '/', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			92 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '.', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			93 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '%', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			94 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '&', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			95 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '|', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			96 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '^', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			97 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '<<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			98 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '>>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			99 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '**', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			100 => fn() => $this->semValue = new Expr\AssignOpNode($this->semStack[$pos - 2], '??', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			101 => fn() => $this->semValue = new Expr\PostOpNode($this->semStack[$pos - 1], '++', $this->startTokenStack[$pos - 1]->line),
			102 => fn() => $this->semValue = new Expr\PreOpNode($this->semStack[$pos], '++', $this->startTokenStack[$pos - 1]->line),
			103 => fn() => $this->semValue = new Expr\PostOpNode($this->semStack[$pos - 1], '--', $this->startTokenStack[$pos - 1]->line),
			104 => fn() => $this->semValue = new Expr\PreOpNode($this->semStack[$pos], '--', $this->startTokenStack[$pos - 1]->line),
			105 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '||', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			106 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '&&', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			107 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], 'or', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			108 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], 'and', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			109 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], 'xor', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			110, 111 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '&', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			112 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '^', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			113 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '.', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			114 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '+', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			115 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '-', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			116 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '*', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			117 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '/', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			118 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '%', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			119 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '<<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			120 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '>>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			121 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '**', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			122 => fn() => $this->semValue = new Expr\InRangeNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			123 => fn() => $this->semValue = new Expr\UnaryOpNode($this->semStack[$pos], '+', $this->startTokenStack[$pos - 1]->line),
			124 => fn() => $this->semValue = new Expr\UnaryOpNode($this->semStack[$pos], '-', $this->startTokenStack[$pos - 1]->line),
			125 => fn() => $this->semValue = new Expr\NotNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->line),
			126 => fn() => $this->semValue = new Expr\UnaryOpNode($this->semStack[$pos], '~', $this->startTokenStack[$pos - 1]->line),
			127 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '===', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			128, 130 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '!==', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			129 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '==', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			131 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '<=>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			132 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			133 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '<=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			134 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			135 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '>=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			136 => fn() => $this->semValue = new Expr\InstanceofNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			139 => fn() => $this->semValue = new Expr\TernaryNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 4]->line),
			140 => fn() => $this->semValue = new Expr\TernaryNode($this->semStack[$pos - 3], null, $this->semStack[$pos], $this->startTokenStack[$pos - 3]->line),
			141 => fn() => $this->semValue = new Expr\TernaryNode($this->semStack[$pos - 2], $this->semStack[$pos], null, $this->startTokenStack[$pos - 2]->line),
			142 => fn() => $this->semValue = new Expr\BinaryOpNode($this->semStack[$pos - 2], '??', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			143 => fn() => $this->semValue = new Expr\IssetNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->line),
			144 => fn() => $this->semValue = new Expr\EmptyNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->line),
			145 => fn() => $this->semValue = new Expr\CastNode('int', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->line),
			146 => fn() => $this->semValue = new Expr\CastNode('float', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->line),
			147 => fn() => $this->semValue = new Expr\CastNode('string', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->line),
			148 => fn() => $this->semValue = new Expr\CastNode('array', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->line),
			149 => fn() => $this->semValue = new Expr\CastNode('object', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->line),
			150 => fn() => $this->semValue = new Expr\CastNode('bool', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->line),
			151 => fn() => $this->semValue = new Expr\ErrorSuppressNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->line),
			153 => fn() => $this->semValue = new Expr\ClosureNode((bool) $this->semStack[$pos - 6], $this->semStack[$pos - 4], [], $this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 7]->line),
			154 => fn() => $this->semValue = new Expr\ClosureNode((bool) $this->semStack[$pos - 10], $this->semStack[$pos - 8], $this->semStack[$pos - 6], $this->semStack[$pos - 5], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 11]->line),
			155 => fn() => $this->semValue = new Expr\ClosureNode((bool) $this->semStack[$pos - 7], $this->semStack[$pos - 5], $this->semStack[$pos - 3], $this->semStack[$pos - 2], null, $this->startTokenStack[$pos - 8]->line),
			156 => fn() => $this->semValue = new Expr\NewNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			162 => fn() => $this->semValue = new Expr\ClosureUseNode($this->semStack[$pos], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 1]->line),
			163, 164 => fn() => $this->semValue = new Expr\FuncCallNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 1]->line),
			165 => fn() => $this->semValue = new Expr\StaticCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->line),
			167, 169, 170 => fn() => $this->semValue = new Node\NameNode($this->semStack[$pos], $this->startTokenStack[$pos]->line),
			171 => fn() => $this->semValue = new Node\FullyQualifiedNameNode(substr($this->semStack[$pos], 1), $this->startTokenStack[$pos]->line),
			172 => fn() => $this->semValue = new Node\RelativeNameNode(substr($this->semStack[$pos], 10), $this->startTokenStack[$pos]->line),
			180 => fn() => $this->semValue = new Scalar\BoolNode(true, $this->startTokenStack[$pos]->line),
			181 => fn() => $this->semValue = new Scalar\BoolNode(false, $this->startTokenStack[$pos]->line),
			182 => fn() => $this->semValue = new Scalar\NullNode($this->startTokenStack[$pos]->line),
			183 => fn() => $this->semValue = new Expr\ConstFetchNode($this->semStack[$pos], $this->startTokenStack[$pos]->line),
			184 => fn() => $this->semValue = new Expr\ClassConstFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			185, 186 => fn() => $this->semValue = new Expr\ArrayNode($this->semStack[$pos - 1]),
			188 => fn() => $this->semValue = Scalar\StringNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->line),
			189 => fn() => $this->semValue = Scalar\EncapsedStringNode::parse($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->line),
			190 => fn() => $this->semValue = Scalar\LNumberNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->line),
			191 => fn() => $this->semValue = Scalar\DNumberNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->line),
			193, 269 => fn() => $this->semValue = new Scalar\StringNode($this->semStack[$pos], $this->startTokenStack[$pos]->line),
			196 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 2], [$this->semStack[$pos - 1]], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line, $this->startTokenStack[$pos]->line),
			197 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 1], [], $this->semStack[$pos], $this->startTokenStack[$pos - 1]->line, $this->startTokenStack[$pos]->line),
			198 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 2], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line, $this->startTokenStack[$pos]->line),
			207 => fn() => $this->semValue = new Expr\ConstFetchNode(new Node\NameNode($this->semStack[$pos], $this->startTokenStack[$pos]->line), $this->startTokenStack[$pos]->line),
			212, 227, 261 => fn() => $this->semValue = new Expr\ArrayAccessNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->line),
			214 => fn() => $this->semValue = new Expr\MethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->line),
			215 => fn() => $this->semValue = new Expr\NullsafeMethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->line),
			216 => fn() => $this->semValue = new Expr\UndefinedsafeMethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->line),
			219, 228, 262 => fn() => $this->semValue = new Expr\PropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			220, 229, 263 => fn() => $this->semValue = new Expr\NullsafePropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			221, 230, 264 => fn() => $this->semValue = new Expr\UndefinedsafePropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			223 => fn() => $this->semValue = new Expr\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->line),
			224 => function () use ($pos) {
				$var = $this->semStack[$pos]->name;
				$this->semValue = \is_string($var)
					? new Node\VarLikeIdentifierNode($var, $this->startTokenStack[$pos]->line)
					: $var;
			},
			225, 231, 232 => fn() => $this->semValue = new Expr\StaticPropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->line),
			239 => fn() => $this->semValue = new Expr\ListNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->line),
			240 => function () use ($pos) {
				$this->semValue = $this->semStack[$pos];
				$end = count($this->semValue) - 1;
				if ($this->semValue[$end] === null) {
					array_pop($this->semValue);
				}
			},
			243, 245 => fn() => $this->semValue = new Expr\ArrayItemNode($this->semStack[$pos], null, false, $this->startTokenStack[$pos]->line),
			244 => fn() => $this->semValue = new Expr\ArrayItemNode($this->semStack[$pos], null, true, $this->startTokenStack[$pos - 1]->line),
			246, 248, 249 => fn() => $this->semValue = new Expr\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 2], false, $this->startTokenStack[$pos - 2]->line),
			247, 250 => fn() => $this->semValue = new Expr\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 3], true, $this->startTokenStack[$pos - 3]->line),
			251, 252 => fn() => $this->semValue = new Expr\ArrayItemNode($this->semStack[$pos], null, false, $this->startTokenStack[$pos - 1]->line, true, $this->startTokenStack[$pos - 1]->line),
			254, 255 => function () use ($pos) {
				$this->semStack[$pos - 1][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 1];
			},
			257 => fn() => $this->semValue = [$this->semStack[$pos - 1], $this->semStack[$pos]],
			258 => fn() => $this->semValue = new Scalar\EncapsedStringPartNode($this->semStack[$pos], $this->startTokenStack[$pos]->line),
			259 => fn() => $this->semValue = new Expr\VariableNode($this->semStack[$pos], $this->startTokenStack[$pos]->line),
			265, 266 => fn() => $this->semValue = new Expr\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->line),
			267 => fn() => $this->semValue = new Expr\ArrayAccessNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 5]->line),
			270 => fn() => $this->semValue = $this->parseOffset($this->semStack[$pos], $this->startTokenStack[$pos]->line),
			271 => fn() => $this->semValue = $this->parseOffset('-' . $this->semStack[$pos], $this->startTokenStack[$pos - 1]->line),
		})();
	}
}

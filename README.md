# magicsq
### Magic Square Maker Quiz Maker for Teachers

Welcome to the Magic Square Quiz Maker. The information here should help you understand what the Magic Square Quiz Maker app can do, as well as how to efficiently operate it. If you have questions, or would like to provide feedback and/or to report a bug, feel free to contact the author, Ken Lowrie, at [www.kenlowrie.com](http://www.kenlowrie.com/).

#### What is a Magic Square?

According to Wikipedia, a [magic square](http://en.m.wikipedia.org/wiki/Magic_square) is "*an arrangement of distinct numbers (i.e. each number is used once), usually integers, in a square grid, where the numbers in each row, and in each column, and the numbers in the forward and backward main diagonals, all add up to the same number*". The Magic Square Quiz Maker App currently supports creating magic squares of odd sizes only (3, 5, 7 & 9), and therefore you will need 9, 25, 49 or 81 terms and definitions, questions and answers, or problems and solutions, in order to use the application.

The Magic Square Quiz Maker will generate *n* variations (where *n* is between 1 and 99) of your input data, each containing its own magic square and jumbled terms, which helps prevent students from directly copying answers from their peers.

#### Getting Started with the App

When you first visit the [Magic Square app page](http://www.kenlowrie.com/apps/magicsquarev1/), you will see several options. To get started, you need to provide some input to the program, so the options for doing that are presented first. There are two options:

1. Load a new input file - This option allows you to browse your local computer for a CSV (Comma Separated Values) file, and upload it to the server for processing. This is the most common method of providing input. Details on CSV Input Files are covered later in this help file.
2. Enter input data manually - This option allows you to manually enter or copy/paste the input data into a text box on your browser. This method is useful in situations where do not have a CSV file on the system, or if you are running on a system that the app is unable to upload a file from. e.g. iOS. In these cases, you can copy/paste the information from another app running on your computer, and then generate the terms from that.

> **NOTE:** The manual data input feature will allow you to edit the data that you previously uploaded, in case you need to tweak it before generating the puzzles. In addition, the manual data input page allows you to generate a set of 3x3 and 5x5 sample sets that are more realistic than the options on the main screen.
 
If no data is currently loaded, the next thing you will see is a series of buttons that allow you to generate test data, reset the app and start over, perform a refresh (useful for clearing status areas), or accessing the online help.

Since the app relies heavily on CSV input, whether from a file or being manually entered, let's go ahead and discuss what a CSV file is.

#### CSV Input Files

The input for the Magic Square tool is a CSV file, or raw data in CSV format. CSV stands for Comma Separated Values, and the file should have one line for every term or item to be included in the square. Each line must have two items separated by a comma. For this reason, if you need a comma included in your definition, you will need to encase it in quotation marks. For example, here are some possible CSV lines:

	Water, liquid that covers 70% of the planet Earth
	US flag colors, "red, white, and blue"
	Texas, greatest state in the union

Another thing to be aware of is that certain characters must be encoded using HTML character encoding in order to display properly. For things you would need to insert as a symbol, like a &deg;, &Delta;, &Lambda;, &Sigma;, &copy;, you must encode them properly. You can find character encodings for these entities on various sites across the Internet, one such reference being: [http://www.w3.org/TR/REC-html40/sgml/entities.html](http://www.w3.org/TR/REC-html40/sgml/entities.html). Here are a few examples of the encodings for the previously discussed symbols:

<table>
    <tr>
        <th>Description</th>
        <th>Symbol</th>
        <th>HTML Code</th>
    </tr>
    <tr>
        <td>Degree</td>
        <td>&deg;</td>
        <td>&amp;deg;</td>
    </tr>
    <tr>
        <td>Delta</td>
        <td>&Delta;</td>
        <td>&amp;Delta;</td>
    </tr>
    <tr>
        <td>Lambda</td>
        <td>&Lambda;</td>
        <td>&amp;Lambda;</td>
    </tr>
    <tr>
        <td>Sigma</td>
        <td>&Sigma;</td>
        <td>&amp;Sigma;</td>
    </tr>
    <tr>
        <td>Copyright</td>
        <td>&copy;</td>
        <td>&amp;copy;</td>
    </tr>
</table>

>REMEMBER:Depending on which magic square size you wish you use, you need **n<sup>2</sup>** total lines in your CSV file. For example, if you plan on using the 5x5 magic square, you will need 25 items. If you prefer, you can make a smaller (3x3) grid with 9 items, or a larger one (7x7) with 49 items. Again, this tool only supports odd-numbered squares. 

The CSV file may be created using a variety of software programs. Microsoft Excel is commonly used, is great for importing data from other sources, but sometimes formats cells in a way that causes errors in the output. Consider using a more rudimentary tool like Wordpad or Notepad. These programs will provide less formatting and give a "cleaner" look at what you are setting up. Just make sure that your file name has the extension CSV (letters after the period that identify the file type). 

In the current version of the app, CSV files can be uploaded from both desktops and Android devices. In other words, your desktop or laptop, or your Android smart phone or tablet device all support uploading data from a CSV file stored locally. Presumably, Windows smart phones would also support this, but that platform has not been tested by me. 

When running the Magic Square Quiz Maker app on iOS devices, however, you cannot upload a CSV file from the local device. Instead, you will need to enter the data manually, most likely by performing a copy/paste operation. You could use an external app to create the data in the proper format, and then copy it to the clipboard, switch to the Magic Square Quiz Maker app running in the browser on the iOS device, and paste it into the text area. For example, use Google Sheets to create the data, then copy/paste that data into the manual data entry page of the Magic Square Quiz Maker app.

#### Columns in Excel (or any Spreadsheet App)

The first column (usually column A in Excel) will become associated with the letter in the magic square, and the second column will be used to fill the number side of the worksheet. Columns can easily be moved in Excel, and if you decide that one item makes more sense in a particular position, that’s how to control it. You might start with the word as the first column and the definition in the second.

#### Customizing Column Headings

By default, this application uses "Term" and "Definition" to label the columns in the printed output (both on screen and in PDF). These terms can be customized by using a special prefix **+++** in the Column A entry, as in the following example:

`+++Problem,Solution`

This would substitute "Problem" for Term and "Solution" for Description within the app. Just like on any line, if you need to insert a comma, just surround the item with quotation marks, for example:

`+++Left Column,"Solution, Description, or Information"`

#### Setting Options

Once the data has been loaded, the next step is to set the various options before generating the quiz data (squares and jumbled term sets). There are several options available:

<dl>
    <dt>Title for Handout</dt>
    <dd>Enter the title to be printed on the handout.</dd>
    <dt>Number of Variants</dt>
    <dd>Enter the number of variants to be created.</dd>
    <dt>Free Term</dt>
    <dd>Enter the number of the free term</dd>
    <dt>Sync Term</dt>
    <dd>Check this box to sync the free term to the aligned term in the jumbled list.</dd>
</dl>

To get additional help about each option, simply click on the header *Quiz Handout Details* or the **?** to expand the detailed help information for filling out the form.

>**NOTE:** This app requires that JavaScript be enabled in your browser in order to gain access to some of the more advanced features. If JavaScript is currently disabled in your browser, the app will print a short message on most pages notifying you that some features are unavailable to you.

Once you have set all of the options, press the **Generate Quiz Data** button to create the sets of magic squares and jumbled terms.

#### Generate Magic Squares

After you press the **Generate Quiz Data** button on the main page, the app will create your puzzle sets, and then preview them in your browser, allowing you to either print or download a PDF for each set you created. For each set, you'll be provided with the magic sum, and also a notice as to which term and definition are aligned in the output. For each puzzle, one term and definition will always be aligned in the output table.

If you do not like the currently generated square or jumbled terms list, simply click the button (non JavaScript browsers), or click on the square or terms list to automatically generate a new variant in place. Once you are happy with the puzzle set, either display the PDF, or download it to your browser.

#### Using the Magic Square Quiz Maker

You can use this tool for an endless variety of activities, from an engaging way to introduce new information to a fresh look at items to be studied for review. Just to give you a quick example, and possibly one you would use with students in guided practice, I am providing [MathFacts.csv](http://www.kenlowrie.com/apps/magicsquarev1/MathFacts.csv). This has 25 very simple math problems, whose answers range (exactly) from 1 to 25. Working the magic square involves finding the math problem on the right that provides the answer for each letter on the left. Once you figure out which items pair up, just write the number from each pair onto the box with that letter on the answer sheet. 

#### Self Correcting

One final note is that using this system is self-correcting for students - if they add up all the boxes in each horizontal row, every row should get the same sum, and that is the same for all the columns (like a Sudoku). The diagonals DO NOT add up to the same value however, in other words, we are generating semi-magic squares, not true-magic squares. Take the following example of a 3x3 Magic Square:

<table>
    <tr>
        <th class="helpsquare">15</th>
        <th class="helpsquare">15</th>
        <th class="helpsquare">15</th>
        <th class="noborder">&nbsp;</th>
    </tr>
    <tr>
        <td>2</td>
        <td>6</td>
        <td>7</td>
        <td class="helpsquare">15</td>
    </tr>
    <tr>
        <td>4</td>
        <td>8</td>
        <td>3</td>
        <td class="helpsquare">15</td>
    </tr>
    <tr>
        <td>9</td>
        <td>1</td>
        <td>5</td>
        <td class="helpsquare">15</td>
    </tr>
</table>

As you can see, each row and each column adds up to 15 when the numbers are placed in the correct location in the square. This allows students to validate that their square has been correctly completed, and if they are close, the rows and columns with errors should be fairly easy to spot. 

#### Summary

This concludes the documentation on the Magic Square Quiz Maker for Teachers app.
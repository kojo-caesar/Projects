<?php
/*
 Print an associative array as an ASCII table. For example let’s say you have this array:

array(
    array(
        'Name' => 'Trixie',
        'Color' => 'Green',
        'Element' => 'Earth',
        'Likes' => 'Flowers'
        ),
    array(
        'Name' => 'Tinkerbell',
&nbs p;       'Element' => 'Air',
        'Likes' => 'Singning',
        'Color' => 'Blue'
        ), 
    array(
        'Element' => 'Water',
        'Likes' => 'Dancing',
        'Name' => 'Blum',
        'Color' => 'Pink'
        ),
);

And expect this output:


+----------+---------+---------+----------+
| Name     | Color   | Element |  Likes   |
+----------+---------+---------+----------+
| Trixie   | Green   | Earth   | Flowers  |
| Tinker   | Bl ue    | Air     | Singing  |
| Blum     | Pink    | Water   | Dancing  |
+----------+---------+---------+----------+

Can you color each column in a different color? 
Please provide a fully unittest covered functionality.
 */
$assoc_array = array(
    array(
        'Name'    => 'Trixie',
        'Color'   => 'Green',
        'Element' => 'Earth',
        'Likes'   => 'Flowers'
    ),
    array(
        'Name'    => 'Tinkerbell',
        'Element' => 'Air',
        'Likes'   => 'Singning',
        'Color'   => 'Blue'
    ),
    array(
        'Element' => 'Water',
        'Likes'   => 'Dancing',
        'Name'    => 'Blum',
        'Color'   => 'Pink'
    ),
);

class AssocArray_to_ASCII {

    protected $rows = array();
    protected $padding = 1;
    protected $headerAlign = 'center';

/*Object init*/
    public function __construct($rows, $padding = 1) {
        $this->rows = $rows;
        $this->padding = $padding;
        $this->columns = $this->_getColumns($rows);
    }

    public function printAssocArray() {
        $output = $this->printHeader();
        $output .= $this->printBody();
        $output .= $this->printFooter();

        return $output;
    }
/*Sorting arrays asc/desc from table by keys*/    
    public function sort($column, $direction = 'asc') {
        $values = array();
        foreach($this->rows as $row) {
            $values[] = $row[$column];
        }
        
        if($direction == 'desc') {
            $sortDirection = SORT_DESC;
        } else {
            $sortDirection = SORT_ASC;
        }
        
        array_multisort($values, $sortDirection, SORT_REGULAR, $this->rows);
    }

/*
    Print Header
*/
    public function printHeader() {
        $columnsTitles = array();
        foreach ($this->columns as $column) {
            $columnsTitles[$column['title']] = $column['title'];
        }

        $output = $this->_printLine();
        $output .= $this->_printRow($columnsTitles, $this->headerAlign);
        $output .= $this->_printLine();

        return $output;
    }

/*
    Print Body
*/
    public function printBody() {
        $output = '';
        foreach ($this->rows as $row) {
            $output .= $this->_printRow($row);
        }

        return $output;
    }

/*
    Print Footer
*/
    public function printFooter() {
        $output = '';
        $output .= $this->_printLine();
        return $output;
    }

    protected function _printLine() {
        $columnsLines = array();
        foreach ($this->columns as $column) {
            $columnsLines[] = str_repeat('-', $column['size'] + ($this->padding * 2));
        }
        $output = implode('+', $columnsLines);
        $output = '+' . $output . '+' . "\n";

        return $output;
    }

    protected function _printRow($row, $align = 'left') {
        $valuesRow = array();
        $colorArray = array("[41m","[43m","[44m","[42m");
        $color = '';
        $i = 0;
        foreach ($this->columns as $column) {
        	
            $value = $row[$column['title']];
            $size = $column['size'];
            $color = $colorArray[$i];
            $i++;
            $valuesRow[] = $this->_printValue($row[$column['title']], $size, $align, $color);
        }
        $output = implode('|', $valuesRow);
        $output = '|' . $output . '|' . "\n";

        return $output;
    }
    
    protected function _printValue($value, $size, $align, $color) {
        $paddingLeft = str_repeat(' ', $this->padding);
        $paddingRight = str_repeat(' ', $this->padding);

        if($align == 'right') {
            $padType = STR_PAD_LEFT;
        } elseif($align == 'center') {
            $padType = STR_PAD_BOTH;
        } else { // if ($align=='left');
            $padType = STR_PAD_RIGHT;
        }
        
        $output = chr(27) . $color . " " . $paddingLeft . str_pad($value, $size, ' ', $padType) . $paddingRight . chr(27) . "[0m";
        
        return $output;
    }

    protected function _getColumns() {
        $columnsTitles = array();
        foreach ($this->rows as $row) {
            foreach ($row as $column => $value) {
                $columnsTitles[$column] = true;
            }
        }
        $columnsTitles = array_keys($columnsTitles);

        $columns = array();
        foreach ($columnsTitles as $columnTitle) {
            $columns[] = array(
                'title' => $columnTitle,
                'size'  => $this->_getColumnSize($columnTitle),
            );
        }

        return $columns;
    }

    protected function _getColumnSize($columnTitle) {
        $maxSize = strlen($columnTitle);

        foreach ($this->rows as $row) {
            if (isset($row[$columnTitle]) && strlen($row[$columnTitle]) > $maxSize) {
                $maxSize = strlen($row[$columnTitle]);
            }
        }

        return $maxSize;
    }
}

$print_array = new AssocArray_to_ASCII($assoc_array);
$print_array->sort('Name', 'desc');
echo $print_array->printAssocArray();
?>
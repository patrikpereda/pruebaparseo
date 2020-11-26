<!DOCTYPE html>
<html lang=”es”>
    <head>
        <meta charset=”UTF-8″ />
        <title>Prueba parseo - Patrik Pereda</title>
        <!--************ Css ************-->
        <link rel="stylesheet" type="text/css" href="assets/css/styles.css" />
    </head>
    <body>
        <?php
            //Includes & Requires.  
            require_once "models/Course.php";
            include_once ("vendors\simple_html_dom.php"); 

            //Get all HTML.
            $html = file_get_html("https://www.masterd.es/cursos-de-formacion-mantenimiento-industrial-g11");
            $titlePage = $html->find("title", 0)->plaintext;
            $getData = $html->find("div[id=listado-grupo]", 0);

            /************ Exercise 1 ************/
            //Get titles of courses.
            $titles_array = array(); 
            for($i = 0; $i < sizeof($getData->find("span")); $i++) {
                $titles_array[] = $getData->find("span")[$i]->plaintext;
            }

            //Get url's of courses.
            $urls_array = array(); 
            foreach($getData->find("a") as $a) { 
              $urls_array[] = $a->href; 
            } 

            /************ Exercise 2 ************/
            //Get descriptions of coursers.
            $descriptions_array = array(); 
            for($i = 0; $i < sizeof($urls_array); $i++) {
                $html = file_get_html($urls_array[$i]);
                $getData = $html->find("div[id=contenido-ficha]", 0)->plaintext;
                $descriptions_array[] = $getData;
            }

            //Transfer all data to models. 
            $courses[] = new Course();  
            for($i = 0; $i < sizeof($titles_array); $i++) {
                $course = new Course(); 
                $course->setTitle($titles_array[$i]);
                $course->setUrl($urls_array[$i]);
                $course->setDescription($descriptions_array[$i]);
                $courses[] = $course;
            }  
                        
            /************ Exercise 3 ************/
            //Operation with CSV. 
            $ids_CSV = array();
            $titles_CSV = array();
            $typesCourse_CSV = array();
            //Open file. 
            $file = fopen("resources/cursos_masterd.csv", "r"); 
            //Get data. 
            while(($data = fgetcsv($file, ",")) == true) {
                $number = count($data);
                $ids_CSV[] = $data[0];
                $titles_CSV[] = $data[1];
                $typesCourse_CSV[] = $data[2];
            }
            //Close file. 
            fclose($file);    

            //Comparation titles. 
            $equalsTitles = array_intersect(array_map('strtolower', $titles_CSV), array_map('strtolower', $titles_array));
            $getTitlesIds = array_keys($equalsTitles);
            $resultIds = array();
            foreach($ids_CSV as $key1 => $value1) {
                foreach($getTitlesIds as $key2 => $value2) {
                    if($key1 == $value2) {
                        $resultIds[] = $value1;
                    }
                }
            }
            $introduceId = 0;
            for($i = 0; $i < sizeof($courses); $i++) {
                if(in_array(strtolower($courses[$i]->getTitle()), $equalsTitles)) {
                    $courses[$i]->setId($resultIds[$introduceId]);
                    $introduceId++;
                    echo "in";
                }
                echo "out";
            }
  
            /************ Exercise 4 ************/
            // Create a new XMLWriter instance 
            $xmlId = new XMLWriter(); 
            
            // Create the output stream to a file 
            $xmlId->openURI('xmlId.xml'); 
            
            // Start the document 
            $xmlId->startDocument('1.0', 'UTF-8'); 
            
            // Start a element 
            $xmlId->startElement('courses');                         
                // Add value to the element 
                for($i = 1; $i < sizeof($courses); $i++) {
                    if($courses[$i]->getId() != null) {
                        $xmlId->startElement('course');
                            $xmlId->startElement('title');
                            $xmlId->text($courses[$i]->getTitle());
                            $xmlId->endElement(); 
                            $xmlId->startElement('url');
                            $xmlId->text($courses[$i]->getUrl());
                            $xmlId->endElement(); 
                            $xmlId->startElement('id');
                            $xmlId->text($courses[$i]->getId());
                            $xmlId->endElement(); 
                            $xmlId->startElement('description');
                            $xmlId->text($courses[$i]->getDescription());
                            $xmlId->endElement();                    
                        $xmlId->endElement(); 
                    }
                }
            // End the element 
            $xmlId->endElement(); 
            
            // End the document 
            $xmlId->endDocument(); 

            // Create a new XMLWriter instance 
            $xmlNoId = new XMLWriter(); 
            
            // Create the output stream to a file 
            $xmlNoId->openURI('xmlNoId.xml'); 
            
            // Start the document 
            $xmlNoId->startDocument('1.0', 'UTF-8'); 
            
            // Start a element 
            $xmlNoId->startElement('courses');                         
                // Add value to the element 
                for($i = 1; $i < sizeof($courses); $i++) {
                    if($courses[$i]->getId() == null) {
                        $xmlNoId->startElement('course');
                            $xmlNoId->startElement('title');
                            $xmlNoId->text($courses[$i]->getTitle());
                            $xmlNoId->endElement(); 
                            $xmlNoId->startElement('url');
                            $xmlNoId->text($courses[$i]->getUrl());
                            $xmlNoId->endElement(); 
                            $xmlNoId->startElement('id');
                            $xmlNoId->text($courses[$i]->getId());
                            $xmlNoId->endElement(); 
                            $xmlNoId->startElement('description');
                            $xmlNoId->text($courses[$i]->getDescription());
                            $xmlNoId->endElement();                    
                        $xmlNoId->endElement(); 
                    }
                }
            // End the element 
            $xmlNoId->endElement(); 
            
            // End the document 
            $xmlNoId->endDocument(); 

        ?>
        <h1><?=$titlePage?></h1>
        <h2>Ejercicio 1</h2>
        <table>
            <tr>
                <th>Título curso</th>
                <th>Enlaces curso</th>
            </tr>
            <?php foreach($titles_array as $keyTitle => $title): ?>
                <?php foreach($urls_array as $keyUrl => $url): ?>
                    <?php if($keyTitle == $keyUrl): ?>
                        <tr>
                            <td><?=$title?></td>
                            <td><?=$url?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </table>
        <br><br><br><br>
        <hr>
        <br><br>
        <h2>Ejercicio 2</h2>
        <table>
            <tr>
                <th>Descripción curso</th>
            </tr>
            <?php foreach($descriptions_array as $keyDescription => $description): ?>
                <tr>
                    <td><?=$description?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br><br><br><br>
        <hr>
        <br><br>
        <h2>Ejercicio 3</h2>
        <table>
            <tr>
                <th>Titulos que coinciden</th>
            </tr>
            <?php foreach($equalsTitles as $keyEqualTitle => $equalTitle): ?>
                <tr>
                    <td><?=$equalTitle?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br><br>
        <table>
            <tr>
                <th>Id</th>
                <th>Título</th>
                <th>Url</th>
                <th>Descripción</th>
            </tr>
            <?php for($i = 1; $i < sizeof($courses); $i++): ?>
                <?php if($courses[$i]->getId() != null): ?>
                    <tr>
                        <td><?=$courses[$i]->getId()?></td>
                        <td><?=$courses[$i]->getTitle()?></td>
                        <td><?=$courses[$i]->getUrl()?></td>
                        <td><?=$courses[$i]->getDescription()?></td>
                    </tr>
                <?php endif; ?>
            <?php endfor; ?>
        </table>
    </body>
</html>
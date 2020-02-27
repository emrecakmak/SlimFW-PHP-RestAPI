<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

    $app = new \Slim\App;
    //Tüm kurslar.
    $app->get('/courses', function (Request $request, Response $response) {
        $db=new DB();
        try {
            $db = $db->connect();
            $courses=$db->query("Select * from courses")->fetchAll(PDO::FETCH_OBJ);
            return $response
                ->withStatus(200)
                ->withHeader("Content-Type", 'application/json')
                ->withJson($courses);
        }catch (PDOException $ex){
            return $response->withJson(
                array(
                    "error"=>array(
                        "text"=>$ex->getMessage(),
                        "code"=>$ex->getCode()
                    )
                ));
        }
    });
    //ID'si verilen kursu getirme.
    $app->get('/course/{id}',function (Request $request, Response $response){
        $id=$request->getAttribute("id");
        $db=new Db();
        $db = $db->connect();

        try{
            $course=$db->query("SELECT * FROM courses WHERE id=$id")->fetchAll(PDO::FETCH_OBJ);
            return $response
                ->withStatus(200)
                ->withHeader("Content-Type","application/json")
                ->withJson($course);
        }catch (PDOException $ex){
            return $response->withJson(array(
                "error"=>array(
                    "text"=> $ex->getMessage(),
                    "code" => $ex->getCode()
            )));
            }
    });
    //Kurs ekleme.
    $app->post('/addcourse',function (Request $request,Response $response){
        $db=new Db();
        $db = $db->connect();

        $title      = $request->getParam("title");
        $couponCode = $request->getParam("couponCode");
        $price      = $request->getParam("price");

        try {
        $statement = "INSERT INTO courses (title,couponCode, price) VALUES(:title, :couponCode, :price)";
        $prepare = $db->prepare($statement);

        $prepare->bindParam("title", $title);
        $prepare->bindParam("couponCode", $couponCode);
        $prepare->bindParam("price", $price);

        $course = $prepare->execute();
            return $response
                ->withStatus(200)
                ->withHeader("Content-Type", 'application/json')
                ->withJson(array(
                    "text"  => "Kurs başarılı bir şekilde eklenmiştir.."
                ));
        }catch (PDOException $ex){
            return $response->withJson(array(
                "error"=>array(
                    "text"=> $ex->getMessage(),
                    "code" => $ex->getCode()
                )));
        }
    });
    //Kurs silme
    $app->delete("/delete/{id}",function (Request $request,Response $response){
        $id=$request->getAttribute("id");
        try {
            $db=new Db();
            $db = $db->connect();

            $statement = "DELETE FROM courses WHERE id = :id";
            $prepare = $db->prepare($statement);
            $prepare->bindParam("id", $id);
            $course = $prepare->execute();

            return $response
                ->withStatus(200)
                ->withHeader("Content-Type","application/json")
                ->withJson(array(
                    "text"=>"Kurs başarıyla silindi"
                ));

        }catch (PDOException $ex){
            return $response->withJson(array(
                "error"=>array(
                    "text"=> $ex->getMessage(),
                    "code" => $ex->getCode()
                )));
        }
    });

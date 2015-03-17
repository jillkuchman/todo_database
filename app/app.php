<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";

    $app = new Silex\Application();
    $app['debug']=true;

    //create a new PHP data object with route to our to_do database
    $DB = new PDO('pgsql:host=localhost;dbname=to_do');

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    //the home page route
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.twig', array('categories' => Category::getAll()));
    });

    // $app->get("/tasks", function() use ($app) {
    //     return $app['twig']->render('tasks.twig', array('tasks' => Task::getAll()));
    // });

    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('categories.twig', array('categories' => Category::getAll()));
    });

    $app->get("/categories/{id}", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('categories.twig', array('category' => $category, 'tasks' => $category->getTasks()));
    });

    $app->post("/relevant_tasks", function() use ($app) {
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $task = new Task($description, $id = null, $category_id);
        $task->save();
        $category = Category::find($category_id);
        return $app['twig']->render('categories.twig', array('category' => $category, 'tasks' => $category->getTasks()));
    });

    $app->get("/show_all_tasks", function() use ($app) {
        $all_tasks = Task::getAll();
        return $app['twig']->render('tasks.twig', array('tasks' => $all_tasks));
    });

    $app->post("/delete_tasks", function() use ($app) {
        Task::deleteAll();
        return $app['twig']->render('index.twig', array ('categories' => Category::getAll()));
    });

    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('index.twig', array('categories' => Category::getAll()));
    });

    $app->post("/delete_categories", function() use ($app) {
        Category::deleteAll();
        return $app['twig']->render('index.twig', array('categories' => Category::getAll()));
    });

    return $app;
?>

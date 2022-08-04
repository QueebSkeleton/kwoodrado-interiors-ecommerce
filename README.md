# Kwoodrado Interiors - E-Commerce Project

An E-Commerce Project submitted as a final requirement in CS Free Elective 2 - E-Commerce in the Computer Science
curriculum offered by the Polytechnic University of the Philippines.

## Business Structure

Kwoodrado Interiors is an imaginary company that sells its own kinds of wooden tables and furnitures. Additionally,
it resells some popular choices from other local brands like Urban Concepts and more.

| Category | Business-to-Consumer (B2C) |
|----------|----------------------------|
| Model    | Private/White Label        |

## E-Commerce Parts

The E-Commerce site involves two major parts:

1. **Front Store** - contains the landing page and the main store pages of the site. Typically these are the pages that the customers buy wooden furnitures from.
1. **Administrator Dashboard** - pages that the owner interacts with to monitor everything that happens in the site.

## Technologies Used

This project does not incorporate trending technologies. It only takes advantage of an easy-to-use stack merely for
the sake of a final requirement.

The E-Commerce built is **monolithic** in nature, and is a typical **web-application** that presents dynamically
generated HyperText pages. Some dynamic pages incorporate asynchronous requests to gain the benefits of a Single-Page App,
but the application overall isn't a SPA.

1. **PHP: HyperText Preprocessor** - the main backing language of the application. Used for easily creating web-applications
that can be launched without difficulty.
1. **MariaDB** - an open-source fork of MySQL, this is the main Relational DBMS that the application interacts with to store
data.
3. **000webhost** - free hosting service to be used. Supports PHP and MariaDB.

## Enhanced Entity-Relationship Diagram (Relational Database Design)

(Not Yet Final)
![image](https://user-images.githubusercontent.com/57407875/118975651-d12c8e80-b9a6-11eb-942a-18d0e7341198.png)

## Instructions to Install

Install the following beforehand:

1. XAMPP - includes both Apache Web Server and a MariaDB instance.

To make this run locally on your machine, do:

1. Download the repository, then extract it inside your XAMPP's htdocs folder. It should look like the following:<br>
`C:\path\to\xampp\htdocs\kwoodrado-interiors-ecommerce`.
1. Initialize your database by executing the `schema.sql` file on your DBMS. It already includes a CREATE DATABASE
statement with the name `kwoodrado_db` so simply plug the SQL file to create everything.
1. Create a `config.ini` file with the following keys:<br>
`db_server` - server IP address e.g `localhost:8000`<br>
`db_user` - dbms instance user associated with the application<br>
`db_password` - password of the user<br>
`db_name` - name of the database to be used by the application i.e `kwoodrado_db`<br>
Place this file inside your htdocs root folder, not inside your application. It should look like the following:<br>
`C:\path\to\xampp\htdocs\config.ini`
1. Finally, configure your XAMPP `DocumentRoot` setting to the application folder. Sample is the following:<br>
`DocumentRoot "C:/xampp/htdocs/kwoodrado-interiors-ecommerce"`

Then, the instance will now run on your local machine. Endpoints are:<br>
`localhost` - the index page of the application
`localhost/admin/dashboard.php` - dashboard. Should redirect you to a login form when not logged in yet.

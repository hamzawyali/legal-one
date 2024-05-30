## About Project

This is a small project that aims to insert logs file with huge amount of data int DB. 
- This app depends on queue worker to insert data
- This app using Docker


## How To Run

- Clone project from public repository: https://github.com/hamzawyali/legal-one/tree/master


- Redirect to project directory


- Run this command to make docker up:  
  #### docker-compose up --build -d

- Run this command to install composer: 
  #### docker compose exec app composer i


- To migrate DB: 
  #### docker compose exec app php bin/console doctrine:migrations:migrate


- To run queue worker: 
  #### docker compose exec app php bin/console messenger:consume async -vv


- Finally run this command to start insertion: 
  #### docker compose exec app php bin/console app:import-logfile logs.log.

## Endpoints

- To run count endpoint with optional query parameters: 
#### {{hostname}}/logs/count?statusCode=201&startDate=2018-08-15&endDate=2018-08-16&serviceName=USER-SERVICE

- To run delete endpoint that truncate DB:
#### {{hostname}}/logs/delete
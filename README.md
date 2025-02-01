# Laravel application that integrates with The Guardian’s API and News API

## Sync News Articles Hourly
1- Add Console Command to fetch The Guardian’s API
2- Store fetched Data In database
3- Schedule command  run php artisan schedule:list 
4-we can set cron job on server to run the previous command 
5-add api to filter articles from database based on detected filters 

## Apply user Auth and favorite module
1- apply user authentication using Passport token
2- add favorite model and define morhhe relation with user model
3- add api to mark model as favorite or un favorite
4-add api to return user favourite articles based on token and user marked favorite  author or categories

## Docrization
1- create DockerFile
2- create Docker compose file to run the services application and mysql
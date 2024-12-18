# TENNIS CHALLENGE API

This API provides the endpoints a client needs to perform a **Tennis Challenge** tournament. Below information gives details about the concept of this tournament.

## OBJECTIVES

Tennis Challenge is a wonderful tournament very easy to implement at any tennis club. Players can challenge each other
(with certain rules) and the results of those matches feed the ranking.                  
This competition doesn't need to have an end date necessarily, but once in a while (one or twice a year) a dinner will
be organized in order to deliver some trophies (fairplay, rocky, top ranking, ....). Main objectives are:
- Promote the activity of our loved sport
- Enhance tennis club social life             
- Introduce club players to competition


## RULES

All matches will be arranged and scored by the players themselves. Fair play spirit must alway be present.
In case of any kind of conflict, the tournament referee will take the decision. Rules:
- The challenger player can challenge any other player with a ranking up to 3 positions over the challenger
- Matches (challenges) will be played to 3 sets, having tie break in all of them            
- If the challenger wins, both players will swap ranking positions

## NOTES

In this project, we have three kind of entities: users (can have 'admin' or 'user' role), players (always have 'user' role) and challenges (= tennis matches). Some endpoints require 'admin' role, some require just 'user' role and one (login) has no role requirement. These are the endpoints and example of expected body in postman:

### No role required:
post('/login'):
{
"email" : "admin@admin",
"password" : "admin"
}

### 'user' or 'admin' roles required:  

**post('/logout'):** requires being logged in  

**get('/top5_players')**  

**get('/player_info/{ranking}')**  

**get('/challenge/{id}')**  


### 'admin' role required:  

**post('/register_user'):**    
    {  
    "name": "Jason",   
    "surname": "Donovan",  
    "email": "jason@sindonovanner",  
    "password": "xxxxxxxx",  
    "role": "admin"
    }  

**post('/delete_user')**  
    {
    "email": "jason@sindonovanner",  
    }  

**put('/edit_user/{id}') (write only fields to be changed)**    
    {  
    "name": "Manolo",   
    "email": "manolo@sindonovanner",  
    "role": "user"  
    }  

**post('/register_player')**  
    {  
    "name" : "Johny",  
    "surname" : "Mac",  
    "email" : "johny@mac",  
    "password" : "xxxxxxx",  
    "height" : 181,  
    "playing_hand" : "left",  
    "backhand_style" : "one hand",  
    "briefing" : "You cannot be serious !!"  
    }  

**put('/edit_player/{id}') (write only fields no be changed)**    
    {  
    "surname" : "Macenroe",  
    "briefing" : "That ball was in !!"  
    }  

**post('/register_challenge')**   
    {  
    "player1_user_id" : 4,  
    "player2_user_id" : 5,  
    "score" :   
        {  
            "player1_set1" : 6,  
            "player2_set1" : 3,  
            "player1_set2" : 4,  
            "player2_set2" : 6,  
            "player1_set3" : 6,  
            "player2_set3" : 2  
        }  
    }  

**delete('/delete_challenge/{id}')**  

**post('/auto_score')**  
    {  
    "player1_user_id" : 4,  
    "player2_user_id" : 5,  
    }  
  
  
## SOFTWARE USED

- Laravel 11.34.2
- MariaDB: mysql  Ver 15.1 Distrib 10.4.28-MariaDB, for osx10.10
- Passprt v12.3.1
- Spatie 6.10.1

## INSTALLATION
- Download the project from:  
https://github.com/egido-quan/Sp5-Laravel-API-REST-Nivel1.git  
- Locate it under /htdocs  
- Create a MariaDB/MySQL database called  
tennis_challenge_api  

## CONTRIBUTIONS
Contributions are welcome:  

- Fork the repository  
- Create a new branch   git checkout -b feature/NewFeature  
- Write your code and commit it:   git commit -m 'New feature'  
- Push it:   git push origin feature/NewFeature  
- Perform a pull request  






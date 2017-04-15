# web_engineering_B00084443_Kevin_Drennan

TAGS
message when logged out
in alphabetical order in create ref page
ensure new tags are unique, do not match previous proposed tag or current tag
tag less than 15 characters
create tag database
add tags when creating ref

ADMIN
in admin view space between table and _base
allow update in admin view
allow delete in admin view

REFS
ref = id, author, title, year, publisher, summary, place of publication, tags, creatorId

CANDITATE TAGS                                                                  1
public - propose new tag through session                                        1
student vote on proposed tags                                                   1
crud personal tags                           
save role on session allow different fileds to be seen at twig using twig if statement
if not logged in creator id = 0, if statement return null when searching
limit on no of votes available to each

tag = eg (php, sql, database, web_engineering) id, tag, creatorId. //users can crud there tags
ref = id, author, title, year, publisher, summary, tags, creatorId
Bib = id, list of refs based on variable like php tag, author or creatorId


public user
    -see public refs
    -see lecturers published bib
    -propose new canditate tag
    -vote on list of new tags (value = 1)
    -submit suggestion for new ref, REF content (author, title, year, publisher) + tags + summary+date created+date edited
    -can add refs to shopping basket list(BIB)
        -change order of items in BIB
    -export BIB as a text file
        -harvard
        
-create database
    -table for ref
    -table for tags (id and tag)
    -propose new tag
    -vote on tag
        
-student
    -can view search refs in collections
        -own collections
        -shared collections from other users
        -all public NURLs
    -can search by 
        -matching tags
        -free text content search
        -by date range created/edited
    -can crud personal tags
    -can crud own refs and collections and tags
    -up/down vote on proposed tags (value=5)
    -can save a BIB
        -can create BIBs
        -can share a BIB with users
    -can update profile
        -extra mark - upload picture
        -can make profile public/private (9username always public for NURLS)
        
-admin
    -can crud student and lecturer and admin accounts                                       1
    
-lecturer
    -all features of student 
    -can publish a BIBS to be public, with a text title and text summary.               

http://stackoverflow.com/questions/1869091/how-to-convert-an-array-to-object-in-php
array to object in proposedtagrepository
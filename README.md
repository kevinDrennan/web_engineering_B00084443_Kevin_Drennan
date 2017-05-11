# web_engineering_B00084443_Kevin_Drennan


SELECT M1.refId
FROM reftags M1, reftags M2
WHERE M1.refId = M2.refId
AND M1.tagId = 6 
AND M2.tagId = 9;
https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=9&cad=rja&uact=8&ved=0ahUKEwiY1O3o9OXTAhXHI8AKHYr6BaEQFghZMAg&url=https%3A%2F%2Focw.mit.edu%2Fcourses%2Furban-studies-and-planning%2F11-521-spatial-database-management-and-advanced-geographic-information-systems-spring-2003%2Flecture-notes%2Flect4.pdf&usg=AFQjCNG1bFgSkyEF8UuMCyI4RGPboHZXGg

http://stackoverflow.com/questions/27371880/php-pdo-found-rows-returns-0/29924433#29924433
for fetching columns

div in search results and createref and viewrefs div into css
Login
error if login as student/student
limit to three checkboxes on search refs

home to return to studentlecturerIndex page when logged in

TAGS
message when logged out
in alphabetical order in create ref page
ensure new tags are unique, do not match previous proposed tag or current tag
tag less than 15 characters
create tag database
add tags when creating ref

Lecturer
publish bib to be public
accept tag to be public 
accept ref to be public

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
array to object in proposedtagrepository"# web_engineering_B00084443_Kevin_Drennan"  git init git add README.md git commit -m "first commit" git remote add origin https://github.com/kevinDrennan/web_engineering_B00084443_Kevin_Drennan.git git push -u origin master

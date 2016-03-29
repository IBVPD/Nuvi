Map Building
===========
1. Build map via file upload
2. Pre-Processors
3. DB Column
4. Validators
5. Drop
6. Reasons for skips or warnings

Build map via file upload
-------------------------

The most common way to build a map will likely be uploading a sample file. The system will read the header column
row and build an initial map. It will try to match any column name in the file with database columns.

When creating a new map the following fields are available: *name, notes, class, version, headerRow, caseLinker, 
labPreference, file*. 

**Lab Preference Field**

The lab preference, and file upload fields are only available on new map creation. The lab preference
field is used only when the map creation receives a file. The purpose of the field is to tell the system what kind of serotype
data to link to. The reason for this is that the fields in a national lab result are identical to those in a reference lab.
Thus this field tells the map builder to select by default one over the other when it finds matching field names. 

**Name, Class & Version Fields**

Everywhere else in the system the map will be referred to through the combination of the name, class and version fields. For
example if you create a map with name EMR EPI, class IBD and version 1, the map will be selectable for import with the 
text field *'EMR EPI (IBD 1)'*. The notes field is useful for administrators that need or want to leave some comments
about the particular mapping's existence. So as a naming convention, there is no need to write the type of import in 
either the name or version fields as it will be included by virtue of the map class field. The version field is also free
form text, so could contain small version differences. Suppose you had two files from a region for whatever reason, or need 
to deal with a historical file. The version field might be where you could put the year or something like 'missing X'. Thus
you could get a map named 'EMR EPI (IBD 2013-Missing DOB) for example.

**Header Row Field**

This tells the map builder which row of the source file contains the column names. All rows before this row will be ignored.

**Case Linker Field**

The case linker field determines how the data gets linked to a particular case. The two choices are **Case Id and Site Code** 
and **Case Id and Verify Country**. The Case Id and Site Code will require that the file has the case id field and the site code. 
When importing for each row the system will use the two fields in a few ways. Within the file itself it will skip any rows after 
the first detected unique combination of case Id and site. For example a file that contains the following data

```
case_ID,site,adm_date,...
1234,AGO-1,2014-01-01,...
3223,AGO-1,2014-02-15,...
1234,AGO-1,2014-01-03,...
...
```

Would import the first and second rows and skip the third one, it would however notify you of a detected duplicate so you could 
investigate if it truly is a duplicate.

The two fields are also used to find pre-existing cases to update. So using the same data example above. If case 1234, AGO-1 already
exists in the database, a new record isn't created, but the existing record is retrieved and any fields in the file are updated.

The second option is the 'Case Id and Verify Country' option. This is designed primarily to handle importing RRL data that doesn't
contain a proper site id. This linker will look for a case with the particular case id within a single country. If there is more 
than one it will not import the data. This makes the assumption that in the surveillance network case id generation is at least 
unique within each country.

Pre-Processors
--------------
DB Column
------------
Validators
------------
Drop
------------
Reasons for skips or warnings
-----------------------------

Importing
==========
Upload form values/meaning

How to read outputs (warnings,errors,successes)

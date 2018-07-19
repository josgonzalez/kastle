import mysql.connector
import psycopg2
from mysql.connector import errorcode

#connection to kastle db
kastle_cnx = mysql.connector.connect(user="joseg496_kippla", password="kastle2.0", 
                                    host="77.104.162.74", database="joseg496_wp324")
kastle_cursor = kastle_cnx.cursor(buffered=True)

#connection to data warehouse
dwh_cnx = psycopg2.connect(dbname = "kla", user = "postgres", password = "5u96gIy0cXcO", host = "52.39.201.209") 
dwh_cursor = dwh_cnx.cursor()

#add user query
add_user = ("INSERT INTO wp_users "
            "(user_login, user_email)"
            "VALUES (%s, %s)")

#add site query
add_site = ("INSERT INTO wp_cimy_uef_data"
            "(USER_ID, FIELD_ID, VALUE)"
            "VALUES (%s, %s, %s)")

#add meta data query
add_usermeta = ("INSERT INTO wp_usermeta"
                "(user_id, meta_key, meta_value)"
                "VALUES (%s, %s, %s)")

#get users information from dwh
get_dwh_data = dwh_cursor.execute("""
SELECT ees."GUID",
  ees."Legal Last name",
  ees."Legal First name",
  ees."User status",
  ees."Site",
  ees."Job Tier",
  ees."Job Title",
  dem."Company email"

FROM hr.employees_by_day ees
LEFT JOIN hr.namely_demographics dem on dem."GUID" = ees."GUID"
WHERE ees."User status" = 'Active Employee'
""")

#get rows of data                             
dwh_user_data = dwh_cursor.fetchall()

#for each row of data add a user
for (userdata) in dwh_user_data:

  #new user data
  first_name = userdata[2]
  last_name = userdata[1]
  site = userdata[4]
  job_tier = userdata[5]
  job_title = userdata[6]
  user_email = userdata[7]
  wp_capabilities = None
  user_ID = None

  #do not add users with these job titles
  if job_title != "After School Instructor" and job_title != "Operations Aide" and job_title != "PE Assistant & After School Instructor":

    #assign wordpress role
    if (site == 'School Success Team (SST)') :
      wp_capabilities = 'a:1:{s:6:"um_sst";b:1;}'
    elif (job_tier == 'School Leader') :
      wp_capabilities = 'a:1:{s:17:"um_school-leaders";b:1;}'
    else :
      wp_capabilities = 'a:1:{s:9:"um_member";b:1;}'

    if "Chief" in job_title or "Managing Director" in job_title :
      wp_capabilities = 'a:1:{s:20:"um_senior-leadership";b:1;}'

    #checks if user is already in kastle db by checking user email
    check_if_user_exist = "SELECT user_email FROM wp_users WHERE user_email ='" + user_email + "'"
    kastle_cursor.execute(check_if_user_exist)
    row = kastle_cursor.fetchone()

    #if user is not already in db then do this
    if (not row):
      #execute add_user query with new_user data
      kastle_cursor.execute(add_user, (user_email, user_email))

      #gets user ID in order to add meta data in wp_usermeta table
      get_ID = "SELECT ID FROM wp_users WHERE user_email ='" + user_email + "'"
      kastle_cursor.execute(get_ID)
      row = kastle_cursor.fetchone()
      for (ID) in row :
        user_ID = ID 

      #user meta data for wp_usermeta
      usermeta = [
        (user_ID, 'first_name', first_name),
        (user_ID, 'last_name', last_name),
        (user_ID, 'wp_capabilities', wp_capabilities),
      ]

      #execute add_usermeta query with usermeta data
      kastle_cursor.executemany(add_usermeta, usermeta)

      #execute add_site query
      kastle_cursor.execute(add_site,(user_ID, 6, site)) 

dwh_cursor.close()
dwh_cnx.close()
kastle_cnx.commit()
kastle_cursor.close()
kastle_cnx.close() 
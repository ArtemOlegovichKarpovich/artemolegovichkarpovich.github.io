import sqlite3 as sql
import sys


year = int(sys.argv[1])
request_str = sys.argv[2]
request_arr = request_str.split("$")
request_arr.append("")

db_add_request = "INSERT INTO timetable VALUES("
db_get_request = "SELECT  date, pair FROM timetable WHERE (\"group\" = \"{}\" AND \"discipline\" = \"{}\" AND \"type\" = \"{}\") ORDER BY pair;".format(request_arr[0], request_arr[2], request_arr[3])
for k in request_arr:
    db_add_request += "\"" + k + "\", "

db_add_request = db_add_request[:-2] + ");"


conn = sql.connect(f"database{year}_{year+1}.db")
cursor = conn.cursor()
cursor.execute(db_add_request)
conn.commit()

cursor.execute(db_get_request)
dates = cursor.fetchall()
list_dates = []
for k in dates:
    s = ""
    s = k[0][6:10] + k[0][2:6] + k[0][0:2]
    s += str(k[1])
    list_dates.append(s)

list_dates = sorted(list_dates)

normal_sorted_dates = []

for k in list_dates:
    s = ""
    s = k[-1] + k[8:10] + k[4:8] + k[0:4]
    normal_sorted_dates.append(s)

i = 1
for k in list_dates:
    db_set_request = "UPDATE timetable SET \"order\" = {} WHERE \"group\" = \"{}\" AND \"date\" = \"{}\" AND \"pair\" = {};".format(i, request_arr[0], normal_sorted_dates[i-1][1:], normal_sorted_dates[i-1][0])
    cursor.execute(db_set_request)
    i += 1

conn.commit()
cursor.close()

# file = open("tt.txt", "w")
# file.write(db_add_request)
# file.write("\n" + db_get_request)
# file.close()






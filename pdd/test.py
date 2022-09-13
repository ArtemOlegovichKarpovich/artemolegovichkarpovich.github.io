import sqlite3 as sql
import sys


def getDisciplines(cursor):
    cursor.execute("SELECT * FROM disciplines ORDER BY course, fullname;")
    base = cursor.fetchall()
    lst_result = []

    first_course = base[0][0]
    str_result = str(courses_fullname_finish.index(base[0][0] + '\n'))
    for k in base:
        if k[0] != first_course:
            first_course = k[0]
            lst_result.append(str_result + '\n')
            str_result = str(courses_fullname_finish.index(k[0] + '\n'))
        str_result += ('?' + k[1] + '$' + k[2] + 
                       '$' + str(k[3]) + '%' + str(k[4]) + 
                       '%' + str(k[5]) + '%' + str(k[6]) +
                       '%' + str(k[7]) + '%' + str(k[8]) +
                       '%' + str(k[9]))
        full_disciplines.append(k[1])
        short_disciplines.append(k[2])

    lst_result.append(str_result)
    file_out = open("disciplines.txt", "w", encoding="UTF-8")
    file_out.writelines(lst_result)
    file_out.close()

year=2022
conn = sql.connect(f"database{year}_{year+1}.db")
cursor = conn.cursor()

getDisciplines(cursor)

cursor.close()

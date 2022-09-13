import sqlite3 as sql
import sys

courses_fullname_finish = []
groups_name_finish = []
full_disciplines = []
short_disciplines = []

pair_types = {'lec':'Л: ',
                'lab':'ЛАБ: ',
                'pra':'Пр.з.: ',
                'sme':'СМЗ: ',
                'zpr':'ЗПРП: ',
                'zac':'Зач: ',
                'ekz':'Экз: ',
                'dru':'Др: '}
pair_types_full = {'lec':'Лекция',
                'lab':'Лабораторное занятие',
                'pra':'Практическое занятие',
                'sme':'Смешанное занятие',
                'zpr':'Занятие под руководством преподавателя',
                'zac':'Зачёт',
                'ekz':'Экзамен',
                'dru':'Другое'}
def getCourses(cursor):
    cursor.execute("SELECT * FROM courses ORDER BY fullname;")
    courses_base = cursor.fetchall()
    global courses_fullname_finish
    courses_shortname_finish = []
    for k in courses_base:
        courses_fullname_finish.append(k[0] + "\n")
        courses_shortname_finish.append(k[1] + "\n")

    if len(courses_fullname_finish) > 0:
        courses_fullname_finish[-1] = courses_base[-1][0]
        courses_shortname_finish[-1] = courses_base[-1][1]

    file_out = open("courses_fullname.txt", "w", encoding="UTF-8")
    file_out.writelines(courses_fullname_finish)
    file_out.close()

    file_out = open("courses_shortname.txt", "w", encoding="UTF-8")
    file_out.writelines(courses_shortname_finish)
    file_out.close()

def getGroups(cursor):
    cursor.execute("SELECT * FROM groups ORDER BY coursefullname;")
    groups_base = cursor.fetchall()
    groups_coursename_finish = []
    global groups_name_finish
    for k in groups_base:
        groups_coursename_finish.append(k[1] + "\n")
        groups_name_finish.append(k[0] + "\n")

    if len(groups_coursename_finish) > 0:
        groups_coursename_finish[-1] = groups_base[-1][1]
        groups_name_finish[-1] = groups_base[-1][0]

    file_out = open("groups_coursename.txt", "w", encoding="UTF-8")
    file_out.writelines(groups_coursename_finish)
    file_out.close()

    file_out = open("groups_name.txt", "w", encoding="UTF-8")
    file_out.writelines(groups_name_finish)
    file_out.close()

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

def getTeachers(cursor):
    cursor.execute("SELECT * FROM teachers ORDER BY surname, name, secondname, position;")
    base = cursor.fetchall()
    lst_result = []

    for k in base:
        str_result = (k[0] + ' ' + k[1] + ' ' + k[2] + '&' + k[3] + '\n')
        lst_result.append(str_result)

    if len(lst_result) > 0:
        lst_result[-1] = (lst_result[-1])[:-1]
    file_out = open("teachers.txt", "w", encoding="UTF-8")
    file_out.writelines(lst_result)
    file_out.close()

def getTimetable(cursor):
    f_dates = open("dates.txt", "r")
    dates = f_dates.readlines()
    for i in range(0, len(dates)):
        dates[i] = dates[i][:-1]

    db_request = "SELECT * FROM timetable WHERE \"date\" = \"{}\" OR \"date\" = \"{}\" OR \"date\" = \"{}\" OR \"date\" = \"{}\" OR \"date\" = \"{}\" OR \"date\" = \"{}\" OR \"date\" = \"{}\";".format(*dates)
    cursor.execute(db_request)
    db_data = cursor.fetchall()

    count_groups = 2 * len(groups_name_finish)

    
    max_id_len = len(str(8*7*2*len(groups_name_finish)))
    subgr_amount = 2*len(groups_name_finish)
    hours = [0 for m in range(subgr_amount)]
    finish_data = []
    id = 0
    s = ""
    no_fio = ""
    no_aud = ""
    for k in db_data:
        id = 2*groups_name_finish.index(k[0][:-2]+"\n") + int(k[0][-1:]) + count_groups * (k[4] - 1 + 8*dates.index(k[1]))
        hours[id%subgr_amount] += 2
        id = (max_id_len - len(str(id)))*"0" + str(id)
        fio = k[5].split(" ")
        if len(fio) == 3:
            fio = fio[0] + " " + fio[1][0] + "." + fio[2][0] + "."
            no_fio = ""
        elif len(fio) == 2:
            fio = fio[0] + " " + fio[1][0] + "."
            no_fio = ""
        elif len(fio) == 1 and fio[0] != '':
            fio = fio[0]
            no_fio = ""
        else:
            fio = ""
            no_fio = "no_fio"

        if k[6] == "":
            no_aud = "no_aud"
        else:
            no_aud = ""
        s = id + pair_types[k[3]] + short_disciplines[full_disciplines.index(k[2])] + ", " + k[6] + "<br>" + fio
        s += "%{}%{}%{}%{}%{}%{}".format(no_fio, no_aud, pair_types_full[k[3]], k[2], k[7], k[5])
        finish_data.append(s + "\n")
    finish_data = sorted(finish_data)
    finish_data.append(str(max_id_len))
    file = open("timetable.txt", "w", encoding="UTF-8")
    file.writelines(finish_data)
    file.close()
    for i in range(subgr_amount):
        hours[i] = str(hours[i]) + "\n"
    file = open("hours.txt", "w", encoding="UTF-8")
    file.writelines(hours)
    file.close()

year = int(sys.argv[1])
#year=2021
conn = sql.connect(f"database{year}_{year+1}.db")
cursor = conn.cursor()

getCourses(cursor)
getGroups(cursor)
getDisciplines(cursor)
getTeachers(cursor)
getTimetable(cursor)

cursor.close()



import sys
import datetime as dt

choosed_year = sys.argv[1]

#global current
#nonlocal weeks, week_of_day_start
inx = 0
weeks = []
choosed_date = '9/1/{}'.format(choosed_year[2:4])
date_list = choosed_date.split('/')

day = int(date_list[1])
month = int(date_list[0])
year = date_list[2]
day_of_week = dt.date(int("20"+year), month, day).isoweekday()

date_start = dt.date(int("20"+year), month, day) - dt.timedelta(day_of_week - 1)
date = date_start

current_day = dt.date.today()
current_day_number = current_day.isoweekday() - 1
date_start = current_day - dt.timedelta(current_day_number)
week_of_day_start = str(date_start.day) if int(date_start.day / 10) != 0 else "0" + str(date_start.day)
week_of_day_start += "." + (str(date_start.month) if int(date_start.month / 10) != 0 else "0" + str(date_start.month))


for k in range(0, 52):
    date_finish = date + dt.timedelta(6)
    weeks.append("{}.{}.{} - {}.{}.{}\n".format(         date.day if len(str(date.day)) >= 2 else "0"+str(date.day), 
                                                        date.month if len(str(date.month)) >= 2 else "0"+str(date.month), 
                                                        date.year,
                                                        date_finish.day if len(str(date_finish.day)) >= 2 else "0"+str(date_finish.day),
                                                        date_finish.month if len(str(date_finish.month)) >= 2 else "0"+str(date_finish.month),
                                                        date_finish.year))
    if week_of_day_start in weeks[-1]:
        weeks[-1] = "t" + weeks[-1]

    date = date_finish + dt.timedelta(1)

weeks[-1] = weeks[-1][:-1]

file = open("weeks.txt", "w")
file.writelines(weeks)

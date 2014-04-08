#!/usr/bin/python
import MySQLdb
import random

db = MySQLdb.connect(host="localhost",
  user="root", passwd="your_password", db="market43")

def get_item_types():
  cursor = db.cursor()
  cursor.execute("SELECT ItemTypeId FROM item_type")
  db.commit()
  numrows = int(cursor.rowcount)
  items = []
  for x in range(0, numrows):
    row = cursor.fetchone()
    items.append(row[0])
  return items

itemtypes = get_item_types()

cursor = db.cursor()
cursor.execute("""SELECT u.UserId, i.ItemType
  FROM user AS u
  INNER JOIN item AS i ON u.UserId = i.OwnerUserId
""")
db.commit()

writecursor = db.cursor()
numrows = int(cursor.rowcount)
for x in range(0, numrows):
  row = cursor.fetchone()
  if (row[1] == 1):
    # Fountain of Money: Add 1000 credits
    writecursor.execute("""INSERT INTO transaction (
      NetBalanceChange, TransactionType, TransactionUser)
      values (1000, 1, """ + str(row[0]) + ")")
    print "giving 1000 credits to user " + str(row[0])
  if (row[1] == 2):
    # Magic Leaf: Add random item 
    item = random.choice(itemtypes)
    writecursor.execute("""INSERT INTO item (ItemType, OwnerUserId)
      values (""" + str(item) + ", " + str(row[0]) + ")")
    print "giving item " + str(item) + " to user " + str(row[0])

db.commit()

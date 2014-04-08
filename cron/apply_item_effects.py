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

def insert_balance_change(user, value):
  return ("INSERT INTO transaction (" +
    "NetBalanceChange, TransactionType, TransactionUser) " +
    "values (" + str(value) + ", 1, " + str(user) + ")")

def insert_item(user, item):
  return ("INSERT INTO item (ItemType, OwnerUserId) " +
    "values (" + str(item) + ", " + str(user) + ")")

writecursor = db.cursor()
numrows = int(cursor.rowcount)
for x in range(0, numrows):
  row = cursor.fetchone()
  user = row[0]
  itemtype = row[1]
  if (itemtype == 1):
    # Fountain of Money: Add 1000 credits
    writecursor.execute(insert_balance_change(user, 1000))
    print "giving 1000 credits to user " + str(user)
  if (itemtype == 2):
    # Magic Leaf: Add random item 
    item = random.choice(itemtypes)
    writecursor.execute(insert_item(user, item))
    print "giving item " + str(item) + " to user " + str(user)
  if (itemtype == 3):
    # Magic Leave: Add 3 random items
    for x in range(3):
      item = random.choice(itemtypes)
      writecursor.execute(insert_item(user, item))
      print "giving item " + str(item) + " to user " + str(user)
  if (itemtype == 4):
    # Magic Leave: Add 10 random items
    for x in range(10):
      item = random.choice(itemtypes)
      writecursor.execute(insert_item(user, item))
      print "giving item " + str(item) + " to user " + str(user)
  if (itemtype == 5):
    # Well of Money: Add 3000 credits
    writecursor.execute(insert_balance_change(user, 3000))
    print "giving 3000 credits to user " + str(user)

db.commit()

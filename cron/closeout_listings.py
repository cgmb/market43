#!/usr/bin/python
import MySQLdb

db = MySQLdb.connect(host="localhost",
  user="root", passwd="your_password", db="market43")

cursor = db.cursor()
cursor.execute("""SELECT ListingId, ListingUserId, ListedItemId
  FROM listing
  WHERE CURRENT_TIMESTAMP > ExpiryTimestamp
  AND Open <> 0
""")
db.commit()

biddercursor = db.cursor()
writecursor = db.cursor()
numrows = int(cursor.rowcount)
for x in range(0, numrows):
  row = cursor.fetchone()
  item = row[2]
  seller = row[1]
  listingid = row[0]
  biddercursor.execute("""SELECT Bidder, Value FROM bid
    WHERE Listing = """ + str(listingid) +
    " ORDER BY Value DESC")
  winner = 0
  saleprice = 0
  numbidderrows = int(cursor.rowcount)
  for y in range(numbidderrows):
    try:
      bidderrow = biddercursor.fetchone()
      winner = bidderrow[0]
      saleprice = bidderrow[1]
      break;
    except:
      break;
  if winner > 0:
    print "transfering " + str(item) + ":" + str(saleprice) + " between " + str(seller) + ":" + str(winner)
    writecursor.execute("UPDATE item SET OwnerUserId = " + str(winner) +
      " WHERE ItemId = """ + str(item))
    writecursor.execute("""INSERT INTO transaction (
      NetBalanceChange, TransactionType, TransactionUser) values (-""" +
      str(saleprice) + ", 0, " + str(winner)  + ")")
    writecursor.execute("""INSERT INTO transaction (
      NetBalanceChange, TransactionType, TransactionUser) values (""" +
      str(saleprice) + ", 0, " + str(seller)  + ")")

  writecursor.execute("UPDATE listing SET Open=false WHERE ListingId = " + str(listingid))
  print "closing transaction " + str(listingid)

db.commit()

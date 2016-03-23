import requests
import json
import os
import sys
from PIL import Image
from StringIO import StringIO
from lxml import html

def getScriptPath():
    return os.path.dirname(os.path.realpath(sys.argv[0]))

def write_unicode(text, charset='utf-8'):
    return text.encode(charset)
#token = 'CAAZABkTpICPwBAN57X2zKC8Dxk03HixXZAulYJzopx77itlbcy88fsHvRIi6BuxY1QcBrEZC9gS7O45dEpdIzGTzn7eSDUxgTGOviFnt2MV1Y05FVR4Lumd35re9ZCdZBmtZCa6IcDdtj1c4KEAJWz77XNZAs6lsXHcnajcf1wvOUdr7JzGE108bZASwXz0wu4H2Cs8MkOG6KQZDZD'

#graph = facebook.GraphAPI(token)
#profile = graph.get_object("me")
#friends = graph.get_connections("me", "friends")
#print friends

pageId = '484621014888154'
# url = 'https://graph.facebook.com/v2.5/' + pageId + '/events/?access_token=' + token

# r = requests.get(url)
# #print r
# print r.status_code

short_lived_token = "CAAZABkTpICPwBAPFrzBM41wJArs8Et7XBCaVzAujfdgF4sZB4ZAbRoNPZCMBp1PYZB6Jqo61Y3vQCoosa8IXmOz6N6cAKS2KMqRst39jU3ZA3ZA29DM1IAyoTjdaX0DvhrDzZBobe8qD3C6JR26cvuG78fv4o3SETqMbBbnXnMzCbchvvK4h26anIYSyxyX2oKVp8ZCPSJJYsBgZDZD"
app_id = "1760941864126716"
account_id = "1039879799392091"
app_secret = "89ef59694498ae2f12e72f482b38283a"
url = "https://graph.facebook.com/v2.2/oauth/access_token?grant_type=fb_exchange_token&client_id="+app_id+"&client_secret="+app_secret+"&fb_exchange_token="+short_lived_token
url = "https://graph.facebook.com/v2.2/me?access_token=CAAZABkTpICPwBADy5RAOk0rz45ilapPtgxyvB9dneiYfeVxlON4nUho8P9iLv02xi8ZCEm8iFU0npwSGv0jIAJJT4GNDFPlCyd6aEAAZCN5VEQbifTWo1ZCW9ufhKtYdkOMXkCQYsOkBZA8ENZCtqMxkERGtIKr2dQajEll9ZApY2zPZCuS3vjxzWBYTyRapZB9IZD"
url = "https://graph.facebook.com/v2.2/1039879799392091/accounts?access_token=CAAZABkTpICPwBADy5RAOk0rz45ilapPtgxyvB9dneiYfeVxlON4nUho8P9iLv02xi8ZCEm8iFU0npwSGv0jIAJJT4GNDFPlCyd6aEAAZCN5VEQbifTWo1ZCW9ufhKtYdkOMXkCQYsOkBZA8ENZCtqMxkERGtIKr2dQajEll9ZApY2zPZCuS3vjxzWBYTyRapZB9IZD"
url = 'https://graph.facebook.com/v2.5/' + pageId + '/events/?access_token=' + 'CAAZABkTpICPwBADy5RAOk0rz45ilapPtgxyvB9dneiYfeVxlON4nUho8P9iLv02xi8ZCEm8iFU0npwSGv0jIAJJT4GNDFPlCyd6aEAAZCN5VEQbifTWo1ZCW9ufhKtYdkOMXkCQYsOkBZA8ENZCtqMxkERGtIKr2dQajEll9ZApY2zPZCuS3vjxzWBYTyRapZB9IZD'

r = requests.get(url)

if r.status_code == 200:
	parsed_json = json.loads(r.text)
	list = parsed_json["data"]
	print list[0]
	print len(list)
	
	path = getScriptPath()
	end = path.rfind("/")
	path = path[:end] + "/html/main/data/facebook.js"
	
	output =  "var facebookEvents = " +json.dumps(list)+";"
	

	coverPhotoArray = []
	for item in list:
		facebookId = item["id"]
		eventPictureRequest = "https://graph.facebook.com/v2.5/" + facebookId + "?fields=cover&" + "access_token=CAAZABkTpICPwBADy5RAOk0rz45ilapPtgxyvB9dneiYfeVxlON4nUho8P9iLv02xi8ZCEm8iFU0npwSGv0jIAJJT4GNDFPlCyd6aEAAZCN5VEQbifTWo1ZCW9ufhKtYdkOMXkCQYsOkBZA8ENZCtqMxkERGtIKr2dQajEll9ZApY2zPZCuS3vjxzWBYTyRapZB9IZD"
		r = requests.get(eventPictureRequest)
		if r.status_code == 200:
			coverPhotoArray.append(json.loads(r.text)["cover"]["source"])
			
	output =  output + "\n"+"var facebookEventCovers = " +json.dumps(coverPhotoArray)+";"
	with open(path, "w") as text_file:
		text_file.write(output)
		


	
	#path = getScriptPath()
	#end = path.rfind("/")
	#path = path[:end] + "/html/main/data/photo.jpg"
	#i = Image.open(StringIO(r.content))
	#i.save(path)


#{"data":[{"access_token":"CAAZABkTpICPwBABoAhCWhipmeAwVSyLDa4OeeFvwX2UMIQYoQkEsTN6CrM4U0ZAhBsfHsIINvrXhYTpu6Jpo4O5ThsBOkis7f4jRyENlp9ypxnkTNiieYZCD4LszJ9KgZBHGR4ZBl8PB3uMeHUK773OqD9VRNHrgGnag8O73QG2nOZC62RZAwGiNn1Tn3Tg6EgZD","category":"Local Business","name":"Appineering","id":"183077938378582","perms":["ADMINISTER","EDIT_PROFILE","CREATE_CONTENT","MODERATE_CONTENT","CREATE_ADS","BASIC_ADMIN"]},{"access_token":"CAAZABkTpICPwBAGGDBHXL0FxZAgWx5H64nUdCdsDjdpxmjv9RapsN9sBTA3znjxPJKVkWHYWLZAnqVuz24elLWu8wxPjosLPFzhygWOjlHSI2gjHxQZBKkJZCpN4fwPzlbLprlmt8bayXZAoonZCIfqZA9aZAwiFZCJNjZBAy2Ky1KZCcr1MsGQkdz8de0mGwfR9Xw4ZD","category":"Community","name":"Rebel Rock","id":"449191638487146","perms":["ADMINISTER","EDIT_PROFILE","CREATE_CONTENT","MODERATE_CONTENT","CREATE_ADS","BASIC_ADMIN"]}],"paging":{"cursors":{"before":"MTgzMDc3OTM4Mzc4NTgy","after":"NDQ5MTkxNjM4NDg3MTQ2"}}}

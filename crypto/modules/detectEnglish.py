# Detect English module
# http://inventwithpython.com/hacking (BSD Licensed)

# To use, type:
#   import modules/detectEnglish
#   detectEnglish.isEnglish(string), returns True or false
# http://invpy.com/dictionary.txt
import re

UPPERLETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
# We need to add any other possible word separator
LETTERS_AND_SPACE = UPPERLETTERS + UPPERLETTERS.lower() + ' \\|\t\n'

def loadDictionary():
    dictionaryFile = open('modules/dictionary.txt', 'r')
    englishWords = {}
    for word in dictionaryFile.read().split('\n'):
        englishWords[word] = None
    dictionaryFile.close()
    return englishWords

ENGLISH_WORDS = loadDictionary()

def getEnglishCount(message):
    message = message.upper()
    message = removeNonLetters(message)
    # Split message by spaces or other possible separators
    possibleWords = re.split(r'[ \|\\]', message)

    if possibleWords == []:
        return 0.0

    matches = 0
    for word in possibleWords:
        if word in ENGLISH_WORDS:
            matches += 1
    return float(matches) / len(possibleWords)

def removeNonLetters(message):
    lettersOnly = []
    for symbol in message:
        if symbol in LETTERS_AND_SPACE:
            lettersOnly.append(symbol)
    return ''.join(lettersOnly)

def isEnglish(message, wordPercentage = 20, letterPercentage = 85):
    wordsMatch = getEnglishCount(message) * 100 >= wordPercentage
    numLetters = len(removeNonLetters(message))
    messageLettersPercentage = (float(numLetters) / len(message)) * 100
    lettersMatch = messageLettersPercentage >= letterPercentage
    return wordsMatch and lettersMatch

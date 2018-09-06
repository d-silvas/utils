import sys
from modules import detectEnglish

#ASCII = '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\] ^_`abcdefghijklmnopqrstuvwxyz{|}~'
ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
ASCII_MAXCHARS = 128

def main():
    if len(sys.argv) == 1:  # error
        exitProgram()

    elif len(sys.argv) == 2: # default (Uppercase letters) mode
        file = open(sys.argv[1], 'r')
        decryptUppercase(file.read().strip())

    elif len(sys.argv) == 3: # rest of modes
        if (sys.argv[1] == '-c'):
            file = open(sys.argv[2], 'r')
            decryptUppercase(file.read().strip())

        elif (sys.argv[1] == '-a'):
            file = open(sys.argv[2], 'r')
            decryptAscii(''.join(file.read().split())) # remove spaces in the file so we have a string

        else:
            exitProgram()

    else: # error
        exitProgram()

def decryptAscii(message):
    # Pick the characters of the message 2 by 2 and turn them into ints
    intsMessage = [int(message[i:i+2], 16) for i in range(0, len(message), 2)]

    for key in range(ASCII_MAXCHARS):
        intsList = [ (intsMessage[i] + key) % ASCII_MAXCHARS for i in range(0, len(intsMessage))]
        decryptedMsg = ''.join([chr(intsList[i]) for i in range(0, len(intsList))])
        if detectEnglish.isEnglish(decryptedMsg):
            print (decryptedMsg)

def decryptUppercase(message):
    for key in range(len(ALPHABET)):
        translated = ''
        message = message.upper()

        for symbol in message:
            if symbol in ALPHABET:
                translated += ALPHABET[(ALPHABET.find(symbol) + key) % len(ALPHABET)]
            else:
                translated += symbol
        #if detectEnglish.isEnglish(translated):
        print('Key #%s: \t%s' % (key, translated))

def exitProgram():
    print('Usage: ./caesar.py [-a] NAME_OF_FILE')
    print('\t-a:\tASCII characters')
    print('\t-c:\tUppercase letters\t[Default]')
    sys.exit()

if __name__ == '__main__':
    main()

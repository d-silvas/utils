import sys
import math

def main():
    file = open(sys.argv[1], 'r')
    message = file.read().strip()
    MAXKEY = len(message)

    #ciphertext = encrypt(key, message)
    for key in range(1, MAXKEY):
        plaintext = decrypt(key, message)
        print(plaintext + '|')
    #print(ciphertext + '|')

def decrypt (key, message):
    columns = math.ceil(len(message) / key)
    rows = key
    shadedBoxes = (columns * rows) - len(message)

    plaintext = [''] * columns

    col = 0
    row = 0

    for symbol in message:
        plaintext[col] += symbol
        col += 1
        if (col == columns) or (col == columns - 1 and row >= rows - shadedBoxes):
            col = 0
            row += 1

    return ''.join(plaintext)


def encrypt (key, message):
    # each string in ciphertext represents a column in the grid
    ciphertext = [''] * key

    for col in range(key):
        pointer = col

        while pointer < len(message):
            ciphertext[col] += message[pointer]
            pointer += key

    return ''.join(ciphertext)

# If the program is run (instead of imported as a module),
# run the main() function
if __name__ == '__main__':
    main()

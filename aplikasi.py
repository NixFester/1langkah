import tkinter as tk
from tkinter import messagebox

def hello():
    messagebox.showinfo("Hello", "Hello, World!")

root = tk.Tk()
root.title("My App")
root.geometry("300x200")

label = tk.Label(root, text="Simple Python GUI")
label.pack(pady=20)

button = tk.Button(root, text="Click Me", command=hello)
button.pack()

root.mainloop()
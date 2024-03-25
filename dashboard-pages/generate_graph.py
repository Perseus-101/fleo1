import matplotlib
matplotlib.use('Agg')  # Use the 'Agg' backend for non-interactive mode
import matplotlib.pyplot as plt
import psycopg2
import sys
from datetime import datetime, timedelta
import matplotlib.dates as mdates
from matplotlib.font_manager import FontProperties

# Load the Outfit font
outfit_regular = FontProperties(fname="../fonts/Outfit-Regular.ttf")
outfit_medium = FontProperties(fname="../fonts/Outfit-Medium.ttf")

# Get the user_id from command-line arguments
user_id = sys.argv[1]

# Connect to the PostgreSQL database
conn = psycopg2.connect(
    host="localhost",
    database="fleo",
    user="postgres",
    password="percy"
)

# Create a cursor object
cur = conn.cursor()

# Retrieve data from the portfolio table for the given user_id
cur.execute("SELECT assetname, purchasevalue, currentvalue FROM portfolio WHERE userid = %s", (user_id,))
portfolio_data = cur.fetchall()

# Close the database connection
cur.close()
conn.close()

# Filter out None values
portfolio_data = [(asset, purchase_value, current_value) for asset, purchase_value, current_value in portfolio_data if purchase_value is not None and current_value is not None]

# Get the minimum and maximum prices
prices = [purchase_value for _, purchase_value, _ in portfolio_data] + [current_value for _, _, current_value in portfolio_data]
min_price = min(prices) if prices else 0
max_price = max(prices) if prices else 0

# Set a fixed start time and time interval (e.g., 1 hour)
start_time = datetime(2024, 3, 25, 9, 0)  # Year, month, day, hour, minute
time_interval = timedelta(hours=1)

# Create the plot
fig, ax = plt.subplots(figsize=(10.5, 6))
ax.set_ylim(0, max_price * 1.1)  # Set the y-axis limits (price range)
ax.set_facecolor('whitesmoke')  # Set background color
ax.grid(True, linestyle='--', linewidth=0.5, color='lightgray')  # Add grid lines

# Plot each asset's purchase value and current value
for asset, purchase_value, current_value in portfolio_data:
    purchase_time = start_time
    current_time = start_time + time_interval

    line, = ax.plot([purchase_time, current_time], [purchase_value, current_value], marker='o', label=asset, linewidth=2)

    # Add annotations for purchase value and current value
    ax.annotate(f"{purchase_value:.2f}", (purchase_time, purchase_value), xytext=(5, 5), textcoords='offset points', fontsize=8, fontproperties=outfit_regular)
    ax.annotate(f"{current_value:.2f}", (current_time, current_value), xytext=(5, 5), textcoords='offset points', fontsize=8, fontproperties=outfit_regular)

    # Add tooltip functionality
    tooltip = plt.gca().get_figure().canvas.mpl_connect("motion_notify_event", lambda event: on_plot_hover(event, line, purchase_time, purchase_value, current_time, current_value))

    # Increment the start time for the next data point
    start_time += time_interval

ax.set_xlabel('Time', fontsize=12, fontproperties=outfit_medium)
ax.set_ylabel('Price (Rupees)', fontsize=12, fontproperties=outfit_medium)
ax.legend(fontsize=10, prop=outfit_medium)

# Rotate x-axis labels for better visibility
ax.xaxis.set_major_formatter(mdates.DateFormatter('%H:%M'))
plt.xticks(rotation=45, fontsize=8, fontproperties=outfit_regular)

# Save the plot as an image file
plt.savefig('graph.png', bbox_inches='tight')


def on_plot_hover(event, line, purchase_time, purchase_value, current_time, current_value):
    # Check if the mouse is over the line
    cont, _ = line.contains(event)
    if cont:
        # Get the x and y data of the line
        x_data, y_data = line.get_data()

        # Find the nearest data point to the mouse position
        idx = min(range(len(x_data)), key=lambda i: abs(x_data[i] - event.xdata))

        # Create the tooltip text
        purchase_date = purchase_time.strftime('%H:%M')
        current_date = current_time.strftime('%H:%M')
        tooltip_text = f"Purchase Value: {purchase_value:.2f} ({purchase_date})\nCurrent Value: {current_value:.2f} ({current_date})"

        # Update the annotation
        line.set_gdata("tooltip_data", tooltip_text)
        line.set_gdata("x_data", x_data[idx])
        line.set_gdata("y_data", y_data[idx])

        # Enable the tooltip
        line.set_gdata("visible", True)

        # Draw the canvas to update the plot
        line.figure.canvas.draw()
    else:
        # Hide the tooltip
        line.set_gdata("visible", False)
        line.figure.canvas.draw()
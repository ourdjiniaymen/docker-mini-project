FROM python:3.13-slim
Label maintainer="Insta docker"

# Install system dependencies
RUN apt update -y && apt install -y \
    python3-dev \
    && apt clean

# Copy the API source code to the root of the container
COPY student_age.py /
COPY requirements.txt /
RUN pip3 install -r /requirements.txt

# Create persistent data folder and declare it as a volume
RUN mkdir /data
VOLUME /data

# Expose the API port
EXPOSE 5000

CMD [ "python3", "./student_age.py" ]
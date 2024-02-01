FROM python:3.9-alpine

WORKDIR /usr/src/app

ENV PYTHONDONTWRITEBYTECODE 1
ENV PYTHONUNBUFFERED 1

RUN apk update \
  && apk add --no-cache gcc python3-dev musl-dev \
  && apk add --no-cache jpeg-dev zlib-dev libjpeg \
  && apk add --no-cache g++ libffi-dev

RUN python3 -m pip install --upgrade pip setuptools wheel

RUN python3 -m pip install --upgrade cython

COPY ./requirements.txt /usr/src/app

RUN pip install -r requirements.txt

COPY . /usr/src/app

EXPOSE 8000

CMD ["python", "manage.py", "runserver", "0.0.0.0:8000"]

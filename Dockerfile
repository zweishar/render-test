FROM drupal:8.9.8-apache

RUN apt-get update && apt-get install -y \
	git \
	rsync \
	sudo \
	unzip \
	vim \
	wget \
	&& docker-php-ext-install bcmath \
	&& docker-php-ext-install mysqli \
	&& docker-php-ext-install pdo \
	&& docker-php-ext-install pdo_mysql

# # Remove the memory limit for the CLI only.
RUN echo 'memory_limit = -1' > /usr/local/etc/php/php-cli.ini

# # Remove the vanilla Drupal project that comes with this image.
RUN rm -rf ..?* .[!.]* *

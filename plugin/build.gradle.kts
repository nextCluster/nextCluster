/*
 * MIT License
 *
 * Copyright (c) 2024 nextCluster
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
plugins {
    id("com.github.johnrengelman.shadow") version "8.1.1"
}

repositories {
    // Velocity
    maven {
        url = uri("https://repo.papermc.io/repository/maven-public/")
    }
    // WaterdogPE
    maven {
        name = "waterdogpeRepoSnapshots"
        url = uri("https://repo.waterdog.dev/snapshots")
    }
    // Nukkit
    maven {
        name = "opencollab-repo-snapshot"
        url = uri("https://repo.opencollab.dev/maven-snapshots")
    }
    //minestom
    maven {
        name = "minestom-repo-snapshot"
        url = uri("https://nexus.bytemc.de/repository/maven-public/")
    }
}

dependencies {
    compileOnly(project(":pre-vm"))

    api(libs.minimessage)
    implementation(libs.minimessage)

    compileOnly("com.velocitypowered:velocity-api:3.3.0-SNAPSHOT")
    annotationProcessor("com.velocitypowered:velocity-api:3.3.0-SNAPSHOT")
    compileOnly("net.md-5:bungeecord-api:1.20-R0.2")
    compileOnly("net.md-5:bungeecord-api:1.20-R0.2")
    compileOnly("io.papermc.paper:paper-api:1.20.4-R0.1-SNAPSHOT")
    compileOnly("net.minestom:common:1.0.0-20240318.151340-1")
    compileOnly("dev.waterdog.waterdogpe:waterdog:2.0.2-SNAPSHOT")
    implementation("net.kyori:adventure-platform-bungeecord:4.3.2")
    compileOnly("cn.nukkit:nukkit:2.0.0-SNAPSHOT")
    implementation(libs.spark)
}

tasks.withType<Jar> {
    val plugin = projectDir.resolve("src/main/resources/plugin.yml")
    if (!plugin.exists()) {
        plugin.createNewFile()
    }
    plugin.writeText(
        """
        name: NextCluster
        author: NextCluster
        version: %s
        main: net.nextcluster.plugin.server.spigot.SpigotClusterPlugin
    """.trimIndent().format(project.version))
}
// Todo: We need separate directories for the different platforms yaml's because the plugin.yml is the same as the waterdog one


tasks.shadowJar {
    archiveFileName.set("nextcluster-plugin.jar")
}